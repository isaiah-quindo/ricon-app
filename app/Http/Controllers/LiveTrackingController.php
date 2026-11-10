<?php

namespace App\Http\Controllers;

use App\Support\LiveTracking\Format;
use App\Support\LiveTracking\LiveRace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Public live tracking: the race centre (latest checkpoint arrivals) and one page per
 * runner. Spectators and crews use it during the race; there are no notifications.
 */
class LiveTrackingController extends Controller
{
    /** Rows the feed shows at first, and how many more each "Show more" adds. */
    private const PAGE = 25;

    public function show(Request $request, string $slug)
    {
        return view('live.show', $this->centre($this->race($slug), $request));
    }

    /**
     * What the race centre polls every 20 seconds: the parts of the page that change,
     * rendered with the same partials as the page itself, plus a little data for Alpine.
     */
    public function centreUpdates(Request $request, string $slug)
    {
        $data = $this->centre($this->race($slug), $request);

        return $this->fresh([
            'regions'  => [
                'stats'   => view('live.partials.stats', $data)->render(),
                'feed'    => view('live.partials.feed-rows', $data)->render(),
                'leaders' => view('live.partials.leaders', $data)->render(),
            ],
            'counts'   => $data['counts'],
            'leader'   => $data['leader'],
            'matches'  => $data['matches'],
            'expected' => $data['expected'],
            'runners'  => $data['followData'],
        ]);
    }

    public function runner(string $slug, string $bib)
    {
        return view('live.runner', $this->runnerData($this->race($slug), $bib));
    }

    /** What the runner page polls every 30 seconds. */
    public function runnerUpdates(string $slug, string $bib)
    {
        $data = $this->runnerData($this->race($slug), $bib);

        return $this->fresh([
            'regions' => [
                'status'   => view('live.partials.runner-status', $data)->render(),
                'stats'    => view('live.partials.runner-stats', $data)->render(),
                'timeline' => view('live.partials.timeline', $data)->render(),
                'next'     => view('live.partials.next-checkpoint-card', $data)->render(),
            ],
        ]);
    }

    /**
     * Everything the race centre shows. The feed is filtered and searched here, and only
     * the first `limit` rows are sent, so the page stays light with hundreds of runners.
     */
    private function centre(LiveRace $race, Request $request): array
    {
        $checkpoints = $race->checkpoints();
        $through = $race->throughCounts();
        $feed = collect($race->feed());

        // An unknown ?cp= just falls back to "All checkpoints".
        $selected = $race->checkpointIndex($request->query('cp')) !== null ? $request->query('cp') : null;
        $query = Str::lower(trim((string) $request->query('q')));
        $limit = min(500, max(self::PAGE, (int) $request->query('limit', self::PAGE)));

        $matching = $feed
            ->when($selected, fn ($rows) => $rows->filter(fn ($row) => $row['cp']['code'] === $selected))
            ->when($query !== '', fn ($rows) => $rows->filter(
                fn ($row) => Str::contains(Str::lower("{$row['runner']['name']} {$row['runner']['bib']}"), $query),
            ));

        $leaderKm = $race->leaderKm();

        return [
            'race'        => $race,
            'checkpoints' => $checkpoints,
            'selected'    => $selected,
            'feed'        => $matching->take($limit)->values()->all(),
            'matches'     => $matching->count(),
            'limit'       => $limit,
            'page'        => self::PAGE,
            // "N through" per card, keyed by code; '' is the "All checkpoints" card.
            'counts'      => ['' => $feed->count()] + array_combine(array_column($checkpoints, 'code'), $through),
            'leader'      => [
                'km'      => $leaderKm,
                'text'    => Format::km($leaderKm),
                'percent' => round($leaderKm / $race->course['finish_km'] * 100, 1),
            ],
            'leaders'     => $race->leaders(),
            // Shown when a checkpoint is selected that nobody has reached yet.
            'expected'    => collect($checkpoints)->keys()
                ->filter(fn ($c) => $through[$c] === 0)
                ->mapWithKeys(fn ($c) => [$checkpoints[$c]['code'] => $race->expectedFirstArrival($c)])
                ->filter()
                ->map(fn ($eta) => Format::window($eta)),
            'followData'  => $this->followData($race),
        ];
    }

    private function runnerData(LiveRace $race, string $bib): array
    {
        $runner = $race->runner($bib);
        abort_unless($runner, 404);

        $next = $runner['last'] === null ? 0 : $runner['last'] + 1;
        $inRace = in_array($runner['status'], ['not_started', 'on_course', 'at_station'], true);

        return [
            'race'        => $race,
            'runner'      => $runner,
            'checkpoints' => $race->checkpoints(),
            'next'        => $inRace ? ($race->checkpoints()[$next] ?? null) : null,
            'nextEta'     => $inRace ? $race->eta($runner, $next) : null,
        ];
    }

    /** JSON that browsers and proxies must never cache, or spectators would see old times. */
    private function fresh(array $data)
    {
        return response()->json($data)->header('Cache-Control', 'no-store');
    }

    private function race(string $slug): LiveRace
    {
        $race = LiveRace::load($slug);
        abort_unless($race, 404);

        return $race;
    }

    /**
     * A small summary of every runner for the "Following" panel. Followed bibs live in
     * the viewer's browser, so the page picks the ones it needs from this list.
     */
    private function followData(LiveRace $race): array
    {
        $checkpoints = $race->checkpoints();

        return collect($race->runners)->map(function ($runner) use ($race, $checkpoints) {
            $last = $runner['last'] !== null ? $checkpoints[$runner['last']] : null;
            $next = $checkpoints[($runner['last'] ?? -1) + 1] ?? null;
            $eta = $race->eta($runner);

            return [
                'bib'      => $runner['bib'],
                'name'     => $runner['name'],
                'url'      => route('live.runner', [$race->course['slug'], $runner['bib']]),
                'status'   => $runner['status'],
                'last'     => match (true) {
                    $last === null       => 'Start',
                    $last['is_finish']   => 'Finished',
                    default              => "{$last['label']} {$last['display']}",
                },
                'lastTime' => $last ? Format::time($runner['passes'][$runner['last']]['in']) : null,
                'next'     => $next && ! in_array($runner['status'], ['dnf', 'finished'], true) ? $next['display'] : null,
                'eta'      => $eta ? Format::window($eta) : null,
            ];
        })->all();
    }
}

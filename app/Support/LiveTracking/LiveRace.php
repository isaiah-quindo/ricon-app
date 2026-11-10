<?php

namespace App\Support\LiveTracking;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

/**
 * Everything the live pages show about one race at one moment: the feed, standings,
 * stats and ETAs. It only needs the course, each runner's raw passes and "now", so the
 * ranking and ETA logic can be tested without a database.
 */
class LiveRace
{
    private const CACHE_SECONDS = 10;

    public readonly array $course;

    public readonly CarbonImmutable $now;

    /** @var array<string, array> Runners keyed by bib label, with ranks, splits and status filled in. */
    public readonly array $runners;

    /** @var string[] Bib labels of runners still in the race, best first. */
    private array $standings = [];

    public static function load(string $slug): ?self
    {
        $course = Course::find($slug);
        if (! $course) {
            return null;
        }

        // Every spectator's page asks for updates every 20 seconds, so what's read from the
        // database is shared for a few seconds instead of re-read for each one. Admin saves
        // clear it. It lives in its own cache store (file by default) so a cache hit needs no
        // database round trip at all.
        [$category, $runners] = Cache::store(config('live.cache_store'))->remember(
            self::cacheKey($slug),
            self::CACHE_SECONDS,
            function () use ($course) {
                $category = RaceEntries::category($course);

                return [
                    $category ? [
                        'id'               => $category->id,
                        'bib_start_number' => $category->bib_start_number,
                        'gun_start_at'     => $category->gun_start_at?->toIso8601String(),
                    ] : null,
                    self::withTimes($category ? RaceEntries::runners($course, $category) : [], fn ($time) => $time->toIso8601String()),
                ];
            },
        );
        // The cache won't store objects (config/cache.php "serializable_classes"), so times
        // are cached as text and turned back into dates here.
        $tz = config('live.timezone');
        $runners = self::withTimes($runners, fn ($time) => CarbonImmutable::parse($time)->setTimezone($tz));

        // The gun start set in admin is when the race began. Without it the race hasn't started.
        if ($category['gun_start_at'] ?? null) {
            $course = Course::find($slug, CarbonImmutable::parse($category['gun_start_at']));
        }

        // The bib start number (100, 60) is the prefix volunteers can leave off when typing a bib.
        $course['race_category_id'] = $category['id'] ?? null;
        $course['bib_prefix'] = (string) ($category['bib_start_number'] ?? '');

        return new self($course, $runners, CarbonImmutable::now(config('live.timezone')));
    }

    /** Call after saving times or a DNF so the next page load shows it straight away. */
    public static function forget(string $slug): void
    {
        Cache::store(config('live.cache_store'))->forget(self::cacheKey($slug));
    }

    /** Applies $convert to every time in the runner list: each pass's in and out, and the DNF time. */
    private static function withTimes(array $runners, callable $convert): array
    {
        foreach ($runners as &$runner) {
            foreach ($runner['passes'] as &$pass) {
                $pass['in'] = $convert($pass['in']);
                $pass['out'] = $pass['out'] === null ? null : $convert($pass['out']);
            }
            unset($pass);
            if ($runner['dnf']) {
                $runner['dnf']['at'] = $convert($runner['dnf']['at']);
            }
        }
        unset($runner);

        return $runners;
    }

    private static function cacheKey(string $slug): string
    {
        return "live:runners:{$slug}";
    }

    public function __construct(array $course, array $runners, CarbonImmutable $now)
    {
        $this->course = $course;
        $this->now = $now;
        $this->runners = $this->rankStandings($this->rankPasses($runners));
    }

    public function checkpoints(): array
    {
        return $this->course['checkpoints'];
    }

    /** Index of a checkpoint by its URL code ("as6"), or null. */
    public function checkpointIndex(?string $code): ?int
    {
        $index = array_search($code, array_column($this->checkpoints(), 'code'), true);

        return $index === false ? null : $index;
    }

    public function runner(string $bib): ?array
    {
        return $this->runners[$bib] ?? null;
    }

    public function stats(): array
    {
        $statuses = array_count_values(array_column($this->runners, 'status'));

        return [
            'race_seconds' => $this->started() ? (int) $this->course['gun']->diffInSeconds($this->now) : 0,
            'on_course'    => ($statuses['on_course'] ?? 0) + ($statuses['at_station'] ?? 0),
            'finished'     => $statuses['finished'] ?? 0,
            'dnf'          => $statuses['dnf'] ?? 0,
        ];
    }

    /** @return array<int, int> How many runners have reached each checkpoint, by index. */
    public function throughCounts(): array
    {
        return array_map(
            fn ($c) => count(array_filter($this->runners, fn ($r) => isset($r['passes'][$c]))),
            array_keys($this->checkpoints()),
        );
    }

    /** Furthest km reached by anyone still in the race. */
    public function leaderKm(): float
    {
        $leader = $this->runners[$this->standings[0] ?? null] ?? null;

        return $leader && $leader['last'] !== null ? $this->checkpoints()[$leader['last']]['km'] : 0.0;
    }

    /** Every checkpoint arrival so far, newest first. */
    public function feed(): array
    {
        $rows = [];
        foreach ($this->runners as $runner) {
            foreach ($runner['passes'] as $c => $pass) {
                $rows[] = [
                    'runner' => $runner,
                    'cp'     => $this->checkpoints()[$c],
                    'pass'   => $pass,
                    'state'  => $this->stateAt($runner, $c),
                ];
            }
        }

        usort($rows, fn ($a, $b) => $b['pass']['in'] <=> $a['pass']['in']);

        return $rows;
    }

    /** Top 2 overall and the first woman. */
    public function leaders(): array
    {
        $inRace = array_map(fn ($bib) => $this->runners[$bib], $this->standings);
        $women = array_values(array_filter($inRace, fn ($r) => $r['sex'] === 'F'));

        return [
            'overall' => array_slice($inRace, 0, 2),
            'female'  => $women[0] ?? null,
        ];
    }

    /** "At AS6 aid station · 3 min", "On course to Kotkot", "Finished · 15:20:11", "DNF at AS2 · Missed cutoff", "Starts 3:00 AM Sat" */
    public function statusText(array $runner): string
    {
        $checkpoints = $this->checkpoints();
        $last = $runner['last'] !== null ? $checkpoints[$runner['last']] : null;

        return match ($runner['status']) {
            'at_station'  => "At {$last['label']} aid station · "
                . (int) $runner['passes'][$runner['last']]['in']->diffInMinutes($this->now) . ' min',
            'finished'    => 'Finished · ' . Format::duration($this->elapsed($runner), true),
            'not_started' => $this->course['gun_set'] ? 'Starts ' . Format::timeWithDay($this->course['gun']) : 'Waiting for the start',
            'dnf'         => "DNF at {$checkpoints[$runner['dnf']['index']]['label']} · {$runner['dnf']['reason']}",
            default       => 'On course to ' . $checkpoints[($runner['last'] ?? -1) + 1]['display'],
        };
    }

    /** True once the gun start has been set in admin and that moment has passed. */
    public function started(): bool
    {
        return $this->course['gun_set'] && $this->now->greaterThanOrEqualTo($this->course['gun']);
    }

    /** Seconds from the gun to the finish or the DNF, or to now while still racing. */
    public function elapsed(array $runner): int
    {
        if ($runner['status'] === 'not_started') {
            return 0;
        }

        $end = match ($runner['status']) {
            'finished' => $runner['passes'][$runner['last']]['in'],
            'dnf'      => $runner['dnf']['at'],
            default    => $this->now,
        };

        return max(0, (int) $this->course['gun']->diffInSeconds($end));
    }

    public function eta(array $runner, ?int $target = null): ?array
    {
        $target ??= ($runner['last'] ?? -1) + 1;

        return Eta::window($runner, $this->course, $target, $this->now);
    }

    /** Earliest window in which anyone could reach a checkpoint nobody has reached yet. */
    public function expectedFirstArrival(int $target): ?array
    {
        $windows = array_filter(array_map(
            fn ($r) => ($r['last'] ?? -1) < $target ? $this->eta($r, $target) : null,
            $this->runners,
        ));
        usort($windows, fn ($a, $b) => $a['from'] <=> $b['from']);

        return $windows[0] ?? null;
    }

    /**
     * What the feed says about a runner at a checkpoint: still at the aid station,
     * departed, finished there, or dropped out there.
     */
    private function stateAt(array $runner, int $c): string
    {
        return match (true) {
            $this->checkpoints()[$c]['is_finish']                    => 'finished',
            ($runner['dnf']['index'] ?? null) === $c                 => 'dnf',
            $c === $runner['last'] && $runner['status'] === 'at_station' => 'at_station',
            default                                                  => 'departed',
        };
    }

    /** Adds rank, gender rank, rank change and split to every pass, plus each runner's status. */
    private function rankPasses(array $runners): array
    {
        foreach (array_keys($this->course['checkpoints']) as $c) {
            $arrived = array_filter($runners, fn ($r) => isset($r['passes'][$c]));
            uasort($arrived, fn ($a, $b) => $a['passes'][$c]['in'] <=> $b['passes'][$c]['in']);

            $position = 0;
            $byGender = ['M' => 0, 'F' => 0];
            foreach (array_keys($arrived) as $bib) {
                $runners[$bib]['passes'][$c]['rank'] = ++$position;
                $runners[$bib]['passes'][$c]['gender_rank'] = ++$byGender[$runners[$bib]['sex']];
            }
        }

        foreach ($runners as $bib => $runner) {
            $previous = null;
            foreach ($runner['passes'] as $c => $pass) {
                // Positive means they moved up (e.g. 12th → 9th is +3).
                $runner['passes'][$c]['rank_change'] = $previous ? $previous['rank'] - $pass['rank'] : null;
                $runner['passes'][$c]['split'] = (int) ($previous['in'] ?? $this->course['gun'])->diffInSeconds($pass['in']);
                $previous = $runner['passes'][$c];
            }

            $last = array_key_last($runner['passes']);
            $runner['last'] = $last;
            $runner['status'] = match (true) {
                $runner['dnf'] !== null                                  => 'dnf',
                $last !== null && $this->course['checkpoints'][$last]['is_finish'] => 'finished',
                $last !== null && $runner['passes'][$last]['out'] === null => 'at_station',
                $last === null && ! $this->started()                     => 'not_started',
                default                                                  => 'on_course',
            };
            $runner['category'] = ($runner['sex'] === 'F' ? 'Women' : 'Men') . ' ' . $runner['age_group'];

            $runners[$bib] = $runner;
        }

        return $runners;
    }

    /**
     * Current overall and gender positions: furthest checkpoint first, then whoever got
     * there earliest. Runners who dropped out, or haven't reached a checkpoint, have none.
     */
    private function rankStandings(array $runners): array
    {
        // Nobody has a position until they reach their first checkpoint.
        $inRace = array_filter($runners, fn ($r) => $r['status'] !== 'dnf' && $r['last'] !== null);
        $lastIn = fn ($r) => $r['last'] === null ? null : $r['passes'][$r['last']]['in'];
        uasort($inRace, fn ($a, $b) => [$b['last'] ?? -1, $lastIn($a)] <=> [$a['last'] ?? -1, $lastIn($b)]);

        $this->standings = array_keys($inRace);
        $byGender = ['M' => 0, 'F' => 0];

        foreach ($runners as $bib => $runner) {
            $runners[$bib]['position'] = null;
            $runners[$bib]['gender_position'] = null;
        }
        foreach ($this->standings as $i => $bib) {
            $runners[$bib]['position'] = $i + 1;
            $runners[$bib]['gender_position'] = ++$byGender[$runners[$bib]['sex']];
        }

        return $runners;
    }
}

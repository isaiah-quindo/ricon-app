<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckpointPass;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Support\LiveTracking\Format;
use App\Support\LiveTracking\LiveRace;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Race-day admin for live tracking: overview, timing entry at a checkpoint, per-runner
 * time corrections and DNFs. Volunteers at checkpoints use admin accounts.
 */
class LiveTrackingController extends Controller
{
    public const DNF_REASONS = ['Withdrew', 'Missed cutoff', 'Medical'];

    public function index(string $slug = '100km')
    {
        $race = $this->race($slug);
        $checkpoints = $race->checkpoints();

        // Per checkpoint: who is there now, who should have arrived by now, who dropped there.
        $rows = collect($checkpoints)->map(fn ($cp, $c) => [
            'cp'         => $cp,
            'through'    => $race->throughCounts()[$c],
            'at_station' => collect($race->runners)->where('status', 'at_station')->where('last', $c)->count(),
            'overdue'    => collect($race->runners)
                ->filter(fn ($r) => ($r['last'] ?? -1) + 1 === $c && ($race->eta($r)['overdue'] ?? false))
                ->count(),
            'dnf'        => collect($race->runners)->filter(fn ($r) => ($r['dnf']['index'] ?? null) === $c)->count(),
        ]);

        // Leader first, runners who dropped out at the bottom.
        $runners = collect($race->runners)->sortBy(fn ($r) => $r['position'] ?? PHP_INT_MAX);

        // What "Reset race" would delete, so the confirmation can say so.
        $resetCounts = [
            'times' => collect($race->runners)->sum(fn ($r) => count($r['passes'])),
            'dnfs'  => collect($race->runners)->whereNotNull('dnf')->count(),
        ];

        return view('admin.live.index', compact('race', 'checkpoints', 'rows', 'runners', 'resetCounts'));
    }

    public function timing(Request $request, string $slug)
    {
        $race = $this->race($slug);
        $feed = $race->feed();

        // Volunteers usually come back to the same checkpoint, so default to the busiest one right now.
        $selected = $race->checkpointIndex($request->query('cp')) ?? $race->checkpointIndex($feed[0]['cp']['code'] ?? null) ?? 0;

        return view('admin.live.timing', [
            'race'        => $race,
            'checkpoints' => $race->checkpoints(),
            'selected'    => $selected,
            'recent'      => $this->recentEntries($feed),
            // What the console needs to check a typed bib, keyed by bib label.
            'lookup'      => collect($race->runners)->map(fn ($r) => [
                'name'   => $r['name'],
                'status' => $r['status'],
                'passes' => $this->passTimes($race, $r),
            ]),
        ]);
    }

    /**
     * Records an In or Out time from the timing console. Answers in JSON so the console can
     * stay on the page and be ready for the next runner straight away.
     */
    public function recordPass(Request $request, string $slug)
    {
        $race = $this->race($slug);
        $data = $request->validate([
            'bib'        => ['required', 'string', 'max:12'],
            'checkpoint' => ['required', Rule::in(array_column($race->checkpoints(), 'code'))],
            'type'       => ['required', 'in:in,out'],
            'time'       => ['required', 'date_format:H:i'],
        ]);

        $runner = $this->findRunner($race, $data['bib']);
        if (! $runner) {
            throw ValidationException::withMessages(['bib' => "No runner with bib {$data['bib']} in this race."]);
        }

        $c = $race->checkpointIndex($data['checkpoint']);
        $at = $this->resolveTime($race, $runner, $c, $data['type'], $data['time']);

        $pass = CheckpointPass::firstOrNew([
            'registration_id' => $runner['registration_id'],
            'checkpoint'      => $data['checkpoint'],
        ]);
        $pass->{$data['type'] . '_at'} = $this->forStorage($at);
        $pass->recorded_by = $request->user()->id;
        $pass->save();
        LiveRace::forget($slug);

        return response()->json([
            'id'     => $pass->id,
            'bib'    => $runner['bib'],
            'name'   => $runner['name'],
            'type'   => $data['type'] === 'in' ? 'In' : 'Out',
            'at'     => Format::time($at),
            // So the console's duplicate warnings stay accurate without a reload.
            'passes' => [$data['checkpoint'] => [
                'in'  => $pass->in_at ? Format::time($pass->in_at->setTimezone(config('live.timezone'))) : null,
                'out' => $pass->out_at ? Format::time($pass->out_at->setTimezone(config('live.timezone'))) : null,
            ]],
        ]);
    }

    /** Undo from the timing console: clears the In or Out time, and the row once both are gone. */
    public function undoPass(Request $request, string $slug, CheckpointPass $pass)
    {
        $race = $this->race($slug);
        abort_unless($pass->registration->race_category_id === $race->course['race_category_id'], 404);
        $type = $request->validate(['type' => ['required', 'in:in,out']])['type'];

        $pass->{$type . '_at'} = null;
        $pass->in_at || $pass->out_at ? $pass->save() : $pass->delete();
        LiveRace::forget($slug);

        return response()->json(['ok' => true]);
    }

    /**
     * Records when the gun went off. This is the source of truth for the race start: race
     * time, splits and ETAs all count from it, and until it's set the race hasn't started.
     */
    public function setGun(Request $request, string $slug)
    {
        $race = $this->race($slug);

        if ($request->boolean('now')) {
            $gun = CarbonImmutable::now(config('live.timezone'))->startOfMinute();
        } else {
            $request->validate(['gun_start' => ['required', 'date_format:Y-m-d\TH:i']], [
                'gun_start.required' => 'Enter the time the gun went off.',
            ]);
            $gun = CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $request->input('gun_start'), config('live.timezone'));
        }

        RaceCategory::whereKey($race->course['race_category_id'])->update(['gun_start_at' => $this->forStorage($gun)]);
        LiveRace::forget($slug);

        $message = "{$race->course['title']} gun start set to " . Format::timeWithDay($gun) . '.';
        // The dialog is prefilled with the scheduled date, so a time typed on another day can
        // easily land in the future. Say so, or the race just stays "not started".
        if ($gun->greaterThan($race->now)) {
            $message .= ' That\'s ' . Format::until($gun, $race->now) . ', so the race shows as not started until then.'
                . ' If the gun already went off, check the date.';
        }

        return back()->with('success', $message);
    }

    /**
     * Clears the race: every recorded time and DNF, and the gun start, so the race is back
     * to not started. For wiping test entries before race day. Registrations are kept.
     */
    public function resetRace(string $slug)
    {
        $race = $this->race($slug);
        $categoryId = $race->course['race_category_id'];
        abort_unless($categoryId, 404);

        [$times, $dnfs] = DB::transaction(function () use ($categoryId) {
            $registrations = Registration::where('race_category_id', $categoryId);

            $times = CheckpointPass::whereIn('registration_id', $registrations->clone()->select('id'))->delete();
            $dnfs = $registrations->clone()->whereNotNull('dnf_checkpoint')
                ->update(['dnf_checkpoint' => null, 'dnf_reason' => null, 'dnf_at' => null]);
            RaceCategory::whereKey($categoryId)->update(['gun_start_at' => null]);

            return [$times, $dnfs];
        });
        LiveRace::forget($slug);

        return back()->with('success', "{$race->course['title']} was reset: "
            . trans_choice(':count checkpoint time|:count checkpoint times', $times) . ' and '
            . trans_choice(':count DNF|:count DNFs', $dnfs) . ' cleared.');
    }

    public function runner(string $slug, string $bib)
    {
        $race = $this->race($slug);
        $runner = $race->runner($bib);
        abort_unless($runner, 404);

        return view('admin.live.runner', [
            'race'        => $race,
            'runner'      => $runner,
            'checkpoints' => $race->checkpoints(),
            'reasons'     => self::DNF_REASONS,
        ]);
    }

    /** Saves the corrections form: every checkpoint's In/Out time, plus DNF status. */
    public function update(Request $request, string $slug, string $bib)
    {
        $race = $this->race($slug);
        $runner = $race->runner($bib);
        abort_unless($runner, 404);

        $codes = array_column($race->checkpoints(), 'code');
        $data = $request->validate([
            'passes'         => ['array'],
            'passes.*.in'    => ['nullable', 'date_format:Y-m-d\TH:i'],
            'passes.*.out'   => ['nullable', 'date_format:Y-m-d\TH:i'],
            'status'         => ['required', 'in:racing,dnf'],
            'dnf_checkpoint' => ['required_if:status,dnf', 'nullable', Rule::in(array_diff($codes, ['finish']))],
            'dnf_reason'     => ['required_if:status,dnf', 'nullable', Rule::in(self::DNF_REASONS)],
        ]);

        // datetime-local values have no timezone; they're race-day local time. The "!" makes
        // seconds 0 instead of copying them from the current time.
        $parse = fn ($value) => $value ? CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $value, config('live.timezone')) : null;

        $times = [];
        foreach ($codes as $code) {
            $in = $parse($data['passes'][$code]['in'] ?? null);
            $out = $parse($data['passes'][$code]['out'] ?? null);
            if ($in && $out && $out->lessThan($in)) {
                throw ValidationException::withMessages(["passes.{$code}.out" => 'The Out time is before the In time.']);
            }
            $times[$code] = [$in, $out];
        }

        $registration = Registration::findOrFail($runner['registration_id']);

        DB::transaction(function () use ($registration, $times, $data, $request) {
            foreach ($times as $code => [$in, $out]) {
                $pass = $registration->checkpointPasses()->firstOrNew(['checkpoint' => $code]);
                if (! $in && ! $out) {
                    $pass->exists && $pass->delete();
                    continue;
                }
                $pass->fill([
                    'in_at'       => $this->forStorage($in),
                    'out_at'      => $this->forStorage($out),
                    'recorded_by' => $request->user()->id,
                ])->save();
            }

            $this->saveDnf($registration, $data['status'] === 'dnf' ? $data['dnf_checkpoint'] : null, $data['dnf_reason'] ?? null);
        });
        LiveRace::forget($slug);

        return back()->with('success', "Saved {$runner['name']}'s times.");
    }

    /** "Mark DNF" from the overview: they dropped at the last checkpoint they reached. */
    public function markDnf(string $slug, string $bib)
    {
        $race = $this->race($slug);
        $runner = $race->runner($bib);
        abort_unless($runner, 404);

        $checkpoint = $race->checkpoints()[$runner['last'] ?? 0]['code'];
        $this->saveDnf(Registration::findOrFail($runner['registration_id']), $checkpoint, 'Withdrew');
        LiveRace::forget($slug);

        return back()->with('success', "{$runner['name']} is marked DNF.");
    }

    public function clearDnf(string $slug, string $bib)
    {
        $race = $this->race($slug);
        $runner = $race->runner($bib);
        abort_unless($runner, 404);

        $this->saveDnf(Registration::findOrFail($runner['registration_id']), null, null);
        LiveRace::forget($slug);

        return back()->with('success', "{$runner['name']} is back in the race.");
    }

    /** Sets or clears a DNF. Keeps the original time when only the checkpoint or reason changes. */
    private function saveDnf(Registration $registration, ?string $checkpoint, ?string $reason): void
    {
        $registration->update([
            'dnf_checkpoint' => $checkpoint,
            'dnf_reason'     => $checkpoint ? $reason : null,
            'dnf_at'         => $checkpoint ? ($registration->dnf_at ?? now()) : null,
        ]);
    }

    /**
     * Times are worked out in race-day local time, but Eloquent saves a date's wall-clock
     * value as-is. Converting to the app timezone first means 2:15 AM in Manila is stored
     * as 6:15 PM UTC, not 2:15 AM UTC.
     */
    private function forStorage(?CarbonImmutable $time): ?CarbonImmutable
    {
        return $time?->setTimezone(config('app.timezone'));
    }

    /** "35", "035" or "100-035" → the runner, as long as the prefix (if typed) is this race's. */
    private function findRunner(LiveRace $race, string $typed): ?array
    {
        if (! preg_match('/^\s*(?:(\d+)-)?(\d+)\s*$/', $typed, $m)) {
            return null;
        }
        if ($m[1] !== '' && $m[1] !== $race->course['bib_prefix']) {
            return null;
        }

        return $race->runner($race->course['bib_prefix'] . '-' . str_pad((int) $m[2], 3, '0', STR_PAD_LEFT));
    }

    /**
     * The console only asks for a time ("02:15"), but a 100K runs through two nights so the
     * same clock time happens twice. The right one is the first time it comes round after
     * the runner's previous recorded time: their In here (for an Out), or their arrival at
     * an earlier checkpoint, or the gun start set in admin. With none of those (no gun set
     * yet), it's whichever day makes the time closest to now.
     */
    private function resolveTime(LiveRace $race, array $runner, int $c, string $type, string $hhmm): CarbonImmutable
    {
        [$h, $m] = array_map('intval', explode(':', $hhmm));

        $earlier = array_filter($runner['passes'], fn ($i) => $i < $c, ARRAY_FILTER_USE_KEY);
        $after = $type === 'out' && isset($runner['passes'][$c])
            ? $runner['passes'][$c]['in']
            : (end($earlier)['in'] ?? ($race->course['gun_set'] ? $race->course['gun'] : null));

        if (! $after) {
            $today = $race->now->setTime($h, $m);

            return collect([$today->subDay(), $today, $today->addDay()])
                ->sortBy(fn ($time) => abs($time->diffInSeconds($race->now)))
                ->first();
        }

        $at = $after->setTime($h, $m);

        return $at->lessThan($after->startOfMinute()) ? $at->addDay() : $at;
    }

    /** A runner's recorded times by checkpoint code, for the console's duplicate warnings. */
    private function passTimes(LiveRace $race, array $runner): array
    {
        return collect($runner['passes'])->mapWithKeys(fn ($p, $c) => [
            $race->checkpoints()[$c]['code'] => [
                'in'  => Format::time($p['in']),
                'out' => $p['out'] ? Format::time($p['out']) : null,
            ],
        ])->all();
    }

    /** The latest In and Out entries at each checkpoint (8 each), newest first, keyed by checkpoint code. */
    private function recentEntries(array $feed): array
    {
        $entries = [];
        foreach ($feed as $row) {
            $base = ['id' => $row['pass']['id'], 'bib' => $row['runner']['bib'], 'name' => $row['runner']['name']];
            $entries[$row['cp']['code']][] = $base + ['type' => 'In', 'at' => $row['pass']['in']];
            if ($row['pass']['out']) {
                $entries[$row['cp']['code']][] = $base + ['type' => 'Out', 'at' => $row['pass']['out']];
            }
        }

        return array_map(function ($list) {
            usort($list, fn ($a, $b) => $b['at'] <=> $a['at']);

            return array_map(fn ($e) => [...$e, 'at' => Format::time($e['at'])], array_slice($list, 0, 8));
        }, $entries);
    }

    private function race(string $slug): LiveRace
    {
        $race = LiveRace::load($slug);
        abort_unless($race, 404);

        return $race;
    }
}

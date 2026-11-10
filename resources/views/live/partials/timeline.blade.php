@use('App\Support\LiveTracking\Format')
@php
    $last = $runner['last'] ?? -1;
    // The highlighted "current" stop is the latest checkpoint of a runner still racing.
    $racing = in_array($runner['status'], ['not_started', 'on_course', 'at_station'], true);
@endphp

<section aria-labelledby="timeline-heading">
    <h2 id="timeline-heading" class="text-xl font-bold text-white">Checkpoints</h2>

    {{-- A vertical line with a dot per stop. Dots are decoration; each card says its own state in text. --}}
    <ol class="relative ml-2 mt-4 space-y-4 border-l border-white/10">
        <li class="relative pl-6">
            <span class="absolute -left-[7px] top-4 h-3.5 w-3.5 rounded-full bg-gray-500" aria-hidden="true"></span>
            <div class="rounded-2xl border border-white/10 bg-[#111111] px-4 py-3">
                <p class="flex flex-wrap items-baseline justify-between gap-2">
                    <span class="font-semibold text-white">Start</span>
                    @if ($race->course['gun_set'])
                        <span class="font-mono text-sm text-gray-300">{{ Format::time($race->course['gun']) }}</span>
                    @else
                        <span class="text-sm text-gray-400">Scheduled <span class="font-mono">{{ Format::time($race->course['scheduled_gun']) }}</span></span>
                    @endif
                </p>
            </div>
        </li>

        @foreach ($checkpoints as $c => $cp)
            @php
                $pass = $runner['passes'][$c] ?? null;
                $current = $racing && $c === $last;
                $isDnf = ($runner['dnf']['index'] ?? null) === $c;
                // Passed without a recorded time (a volunteer missed them), vs genuinely still ahead.
                $missed = $pass === null && $c < $last;
                $upcoming = $pass === null && ! $missed;
                $eta = $upcoming && $racing ? $race->eta($runner, $c) : null;
            @endphp
            <li class="relative pl-6" @if ($current) aria-current="step" @endif>
                <span @class([
                    'absolute -left-[7px] top-4 h-3.5 w-3.5 rounded-full',
                    'bg-orange-500 ring-4 ring-orange-500/25' => $current,
                    'bg-red-400' => $isDnf,
                    'bg-gray-400' => $pass && ! $current && ! $isDnf,
                    'border-2 border-white/30 bg-[#0d0d0d]' => $upcoming,
                    'bg-gray-600' => $missed,
                ]) aria-hidden="true"></span>

                <div @class([
                    'rounded-2xl border px-4 py-3',
                    'border-orange-500 bg-orange-500/10' => $current,
                    'border-white/10 bg-[#111111]' => ! $current,
                ])>
                    <div class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
                        <p class="min-w-0">
                            <span class="font-mono text-xs font-bold text-orange-400">{{ $cp['label'] }}</span>
                            <span @class(['ml-1 font-semibold', 'text-white' => ! $upcoming, 'text-gray-300' => $upcoming])>{{ $cp['display'] }}</span>
                            <span class="ml-1 font-mono text-xs text-gray-400">{{ Format::km($cp['km']) }}</span>
                        </p>
                        @if ($current)
                            <span class="text-xs font-bold uppercase tracking-wider text-orange-300">Current</span>
                        @elseif ($isDnf)
                            <span class="text-xs font-bold uppercase tracking-wider text-red-300">DNF · {{ $runner['dnf']['reason'] }}</span>
                        @elseif ($upcoming)
                            <span class="text-xs uppercase tracking-wider text-gray-400">Upcoming</span>
                        @elseif ($missed)
                            <span class="text-xs uppercase tracking-wider text-gray-400">No time recorded</span>
                        @endif
                    </div>

                    @if ($pass)
                        <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm sm:grid-cols-4">
                            <div>
                                <dt class="text-xs text-gray-400">{{ $current ? 'Arrived' : 'In' }}</dt>
                                <dd><x-live.time :time="$pass['in']" :now="$current ? $race->now : null" /></dd>
                            </div>
                            @unless ($cp['is_finish'] || $isDnf)
                                <div>
                                    <dt class="text-xs text-gray-400">Out</dt>
                                    <dd class="font-mono text-white">{{ $pass['out'] ? Format::time($pass['out']) : 'Still here' }}</dd>
                                </div>
                            @endunless
                            <div>
                                <dt class="text-xs text-gray-400">Split</dt>
                                <dd class="font-mono tabular-nums text-white">{{ Format::duration($pass['split']) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">{{ $current ? 'New rank' : 'Rank' }}</dt>
                                <dd class="flex items-center gap-2">
                                    <span class="font-mono text-white">{{ Format::ordinal($pass['rank']) }}</span>
                                    <x-live.rank-change :change="$pass['rank_change']" />
                                </dd>
                            </div>
                        </dl>
                    @elseif ($racing && $upcoming)
                        <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400">Expected</dt>
                                <dd class="text-white"><x-live.eta-range :eta="$eta" /></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Cutoff</dt>
                                <dd class="font-mono text-white">{{ $cp['cutoff'] ? Format::timeWithDay($cp['cutoff']) : 'None' }}</dd>
                            </div>
                        </dl>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</section>

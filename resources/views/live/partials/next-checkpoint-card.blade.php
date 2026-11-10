@use('App\Support\LiveTracking\Format')

{{-- Spec fallback: lighter surface plus an orange top edge, since the page itself is already dark. --}}
<section aria-labelledby="next-heading" class="rounded-2xl border border-white/10 border-t-2 border-t-orange-500 bg-[#1a1a1a] p-5">
    @if ($next)
        <p class="text-xs font-semibold uppercase tracking-wider text-orange-400">Next checkpoint</p>
        <h2 id="next-heading" class="mt-1 text-xl font-bold text-white">{{ $next['display'] }}</h2>
        <p class="font-mono text-xs text-gray-400">{{ $next['label'] }} · {{ Format::km($next['km']) }}</p>

        <dl class="mt-4 space-y-3 text-sm">
            <div class="flex items-start justify-between gap-4">
                <dt class="text-gray-400">Expected</dt>
                <dd class="text-right text-white"><x-live.eta-range :eta="$nextEta" /></dd>
            </div>
            <div class="flex items-start justify-between gap-4">
                <dt class="text-gray-400">Distance to go</dt>
                <dd class="font-mono text-white">{{ Format::km($next['km'] - ($runner['last'] !== null ? $checkpoints[$runner['last']]['km'] : 0)) }}</dd>
            </div>
            <div class="flex items-start justify-between gap-4">
                <dt class="text-gray-400">Crew access</dt>
                <dd class="text-white">{{ $next['crew_access'] ? 'Yes' : 'No' }}</dd>
            </div>
            <div class="flex items-start justify-between gap-4">
                <dt class="text-gray-400">Cutoff</dt>
                <dd class="font-mono text-white">{{ $next['cutoff'] ? Format::timeWithDay($next['cutoff']) : 'None' }}</dd>
            </div>
        </dl>

        <p class="mt-4 border-t border-white/10 pt-3 text-xs text-gray-400">
            The window narrows as {{ Str::before($runner['name'], ' ') }} gets closer.
        </p>
    @elseif ($runner['status'] === 'finished')
        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-300">Finished</p>
        <h2 id="next-heading" class="mt-1 text-xl font-bold text-white">{{ Format::duration($race->elapsed($runner), true) }}</h2>
        <p class="mt-2 text-sm text-gray-400">{{ Format::ordinal($runner['position']) }} overall · {{ Format::ordinal($runner['gender_position']) }} among {{ $runner['sex'] === 'F' ? 'women' : 'men' }}</p>
    @else
        <p class="text-xs font-semibold uppercase tracking-wider text-red-300">Did not finish</p>
        <h2 id="next-heading" class="mt-1 text-xl font-bold text-white">{{ $checkpoints[$runner['dnf']['index']]['display'] }}</h2>
        <p class="mt-2 text-sm text-gray-400">{{ $runner['dnf']['reason'] }} at <span class="font-mono">{{ Format::time($runner['dnf']['at']) }}</span></p>
    @endif
</section>

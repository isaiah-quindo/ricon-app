@use('App\Support\LiveTracking\Format')
@php
    $female = $leaders['female'];
    $entries = collect($leaders['overall'])->map(fn ($r) => [
        'label'  => Format::ordinal($r['position']),
        'runner' => $r,
        'note'   => $female && $r['bib'] === $female['bib'] ? '1st woman' : null,
    ]);
    // Only add a separate row for the first woman when she isn't already in the top 2.
    if ($female && ! $entries->contains(fn ($e) => $e['note'])) {
        $entries->push(['label' => '1st woman', 'runner' => $female, 'note' => null]);
    }
@endphp

<section aria-labelledby="leaders-heading" class="rounded-2xl border border-white/10 bg-[#111111] p-5">
    <h2 id="leaders-heading" class="text-lg font-bold text-white">Leaders</h2>
    @if ($entries->isEmpty())
        <p class="mt-2 text-sm text-gray-400">Leaders show here once runners reach the first checkpoint.</p>
    @endif
    <ol class="mt-2 divide-y divide-white/10">
        @foreach ($entries as ['label' => $label, 'runner' => $runner, 'note' => $note])
            @php $cp = $runner['last'] !== null ? $checkpoints[$runner['last']] : null; @endphp
            <li class="flex items-start gap-3 py-3">
                <span class="w-20 shrink-0 pt-3.5 font-mono text-xs font-bold uppercase text-orange-400">{{ $label }}</span>
                <div class="min-w-0">
                    <a href="{{ route('live.runner', [$race->course['slug'], $runner['bib']]) }}"
                        class="inline-flex min-h-[44px] items-center rounded-md font-semibold text-white hover:text-orange-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">{{ $runner['name'] }}</a>
                    @if ($cp)
                        <p class="-mt-2 text-sm text-gray-400">
                            {{ $cp['is_finish'] ? 'Finished' : "{$cp['label']} {$cp['display']}" }} ·
                            <span class="font-mono text-gray-200">{{ Format::time($runner['passes'][$runner['last']]['in']) }}</span>
                        </p>
                    @endif
                    @if ($note)
                        <p class="mt-1 text-xs font-semibold text-orange-300">Also {{ $note }}</p>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</section>

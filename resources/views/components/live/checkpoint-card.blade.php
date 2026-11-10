@props(['code', 'name', 'count', 'countLabel' => 'through', 'label' => null, 'km' => null, 'selected' => false])

{{--
    A real <button>, so it works with keyboard and screen readers. aria-pressed says whether it's
    the active filter, and the aria-pressed: classes style it from that same attribute.
    Expects to sit inside the raceCentre Alpine component (cp, counts, select()).
--}}
<button type="button"
    @click="select(@js($code))"
    :aria-pressed="cp === @js($code)"
    aria-pressed="{{ $selected ? 'true' : 'false' }}"
    class="flex min-h-[44px] w-40 shrink-0 snap-start flex-col rounded-2xl border border-white/10 bg-[#1a1a1a] p-3 text-left transition-colors hover:border-white/25 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400 aria-pressed:border-orange-500 aria-pressed:bg-orange-500/10">
    <span class="flex items-baseline justify-between gap-2 text-xs">
        <span class="font-mono font-bold text-orange-400">{{ $label ?? 'ALL' }}</span>
        @if ($km !== null)
            <span class="font-mono text-gray-400">{{ App\Support\LiveTracking\Format::km($km) }}</span>
        @endif
    </span>
    <span class="mt-1 text-sm font-semibold leading-tight text-white">{{ $name }}</span>
    <span class="mt-auto pt-2 text-xs text-gray-400"><span class="font-mono tabular-nums text-gray-200" x-text="counts[@js($code)]">{{ $count }}</span> {{ $countLabel }}</span>
</button>

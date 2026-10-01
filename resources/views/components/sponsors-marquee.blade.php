{{-- Scrolling "Race Partners" logo strip. Used on the Partners page and the front page. --}}
@props(['showAllLink' => false])

@php
    $sponsors = [
        ['img' => 'title-sponsor', 'h' => 66, 'label' => 'Title Sponsor · Official Shoe Partner'],
        ['img' => 'presentor', 'h' => 56, 'label' => 'Presentor'],
        ['img' => 'hotel', 'h' => 48, 'label' => 'Official Hotel Partner'],
        ['img' => 'energy-gel', 'h' => 42, 'label' => 'Official Energy Gel Partner'],
        ['img' => 'headlamp', 'h' => 34, 'label' => 'Official Headlamp Partner'],
        ['img' => 'trekking-pole', 'h' => 38, 'label' => 'Official Trekking Pole Partner'],
        ['img' => 'hydration', 'h' => 44, 'label' => 'Official Hydration Partner'],
        ['img' => 'electrolyte', 'h' => 52, 'label' => 'Official Electrolyte Partner'],
        ['img' => 'shoe-laundry', 'h' => 34, 'label' => 'Official Shoe Laundry Partner'],
        ['img' => 'watch', 'h' => 32, 'label' => 'Official Watch Partner'],
    ];
@endphp

@once
<style>
    .partners-marquee {
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-mask-image: linear-gradient(90deg, transparent 0, #000 64px, #000 calc(100% - 64px), transparent 100%);
        mask-image: linear-gradient(90deg, transparent 0, #000 64px, #000 calc(100% - 64px), transparent 100%);
    }
    .partners-marquee::-webkit-scrollbar { display: none; }
    .partners-marquee-track {
        width: max-content;
        animation: partners-scroll 42s linear infinite;
        cursor: grab;
    }
    .partners-marquee-track:active { cursor: grabbing; }
    .partners-marquee:hover .partners-marquee-track,
    .partners-marquee-track.paused { animation-play-state: paused; }
    @keyframes partners-scroll {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
        .partners-marquee-track { animation: none; }
    }
</style>

@push('scripts')
<script>
    // Drag-to-scroll on the sponsor marquee (pauses auto-scroll once the user grabs it)
    document.querySelectorAll('.partners-marquee').forEach(function (viewport) {
        var track = viewport.querySelector('.partners-marquee-track');
        var isDown = false, startX, scrollLeft;

        viewport.addEventListener('mousedown', function (e) {
            isDown = true;
            track.classList.add('paused');
            startX = e.pageX;
            scrollLeft = viewport.scrollLeft;
        });
        window.addEventListener('mouseup', function () { isDown = false; });
        viewport.addEventListener('mouseleave', function () { isDown = false; });
        window.addEventListener('mousemove', function (e) {
            if (!isDown) return;
            e.preventDefault();
            viewport.scrollLeft = scrollLeft - (e.pageX - startX);
        });

        viewport.addEventListener('touchstart', function (e) {
            track.classList.add('paused');
            startX = e.touches[0].pageX;
            scrollLeft = viewport.scrollLeft;
        }, { passive: true });
        viewport.addEventListener('touchmove', function (e) {
            viewport.scrollLeft = scrollLeft - (e.touches[0].pageX - startX);
        }, { passive: true });
    });
</script>
@endpush
@endonce

<section {{ $attributes->merge(['class' => 'py-16 border-t border-white/10']) }}>
    <div class="mx-auto px-8 mb-8 flex flex-wrap items-end justify-between gap-4" style="max-width:1280px;">
        <div>
            <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">Sponsors</p>
            <h2 class="text-3xl font-bold text-white">Race Partners</h2>
        </div>
        @if ($showAllLink)
            <a href="{{ route('partners') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-300 hover:text-white transition-colors">
                View all partners
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        @endif
    </div>

    <div class="partners-marquee">
        <div class="partners-marquee-track flex items-center">
            @foreach (array_merge($sponsors, $sponsors) as $i => $sponsor)
                <div class="flex-none flex flex-col items-center justify-end gap-3 px-11 h-[150px]" @if ($i >= count($sponsors)) aria-hidden="true" @endif>
                    <img src="/images/partners/{{ $sponsor['img'] }}.png" alt="{{ $sponsor['label'] }}"
                        class="block w-auto object-contain brightness-0 invert" style="height:{{ $sponsor['h'] }}px;" draggable="false" loading="lazy">
                    <span class="text-[11.5px] font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">{{ $sponsor['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

@extends('layouts.public')
@section('title', 'Partners')
@section('og_title', 'Our Partners | The Great Cordillera 100 Ultra Trail')
@section('og_description', 'Meet the brands, agencies, and communities building The North Face Great Cordillera 100 with RiCON.')

@section('head')
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
@endsection

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

    $communities = [
        ['img' => 'dot-car', 'label' => 'Department of Tourism - CAR'],
        ['img' => 'city-of-baguio', 'label' => 'City of Baguio'],
        ['img' => 'itogon', 'label' => 'Municipality of Itogon'],
    ];
@endphp

@section('content')

{{-- ========================================================
         PAGE HEADER
    ======================================================== --}}
<section class="bg-[#0d0d0d] pt-36 pb-16 text-center">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="mx-auto mb-8 w-fit rounded-2xl px-10 py-7" style="background:radial-gradient(ellipse at center, rgba(249,115,22,0.16) 0%, rgba(249,115,22,0) 72%);">
            <img src="/images/partners/tgc100-lockup.png" alt="The North Face Great Cordillera 100"
                class="block mx-auto h-auto" style="width:min(460px,80vw); filter:drop-shadow(0 0 34px rgba(249,115,22,0.28));">
        </div>
        <p class="text-orange-500 text-sm font-semibold mb-2 uppercase tracking-wider">The North Face Great Cordillera 100</p>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Our Partners</h1>
        <p class="text-gray-400 max-w-2xl mx-auto leading-relaxed">
            TGC100 doesn't happen alone. These are the brands, agencies, and communities building this race with us, on the trail, at the aid stations, and everywhere in between.
        </p>
    </div>
</section>

{{-- ========================================================
         RACE PARTNERS (marquee)
    ======================================================== --}}
<section class="bg-[#111111] py-16 border-t border-white/10">
    <div class="mx-auto px-8 mb-8" style="max-width:1280px;">
        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">Sponsors</p>
        <h2 class="text-3xl font-bold text-white">Race Partners</h2>
    </div>

    <div class="partners-marquee">
        <div class="partners-marquee-track flex items-center">
            @foreach (array_merge($sponsors, $sponsors) as $i => $sponsor)
                <div class="flex-none flex flex-col items-center justify-end gap-3 px-11 h-[150px]" @if ($i >= count($sponsors)) aria-hidden="true" @endif>
                    <img src="/images/partners/{{ $sponsor['img'] }}.png" alt="{{ $sponsor['label'] }}"
                        class="block w-auto object-contain brightness-0 invert" style="height:{{ $sponsor['h'] }}px;" draggable="false">
                    <span class="text-[11.5px] font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">{{ $sponsor['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         COMMUNITIES & GOVERNMENT PARTNERS
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-16 border-t border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">In Partnership With</p>
        <h2 class="text-3xl font-bold text-white mb-10">Communities &amp; Government Partners</h2>

        <div class="flex flex-wrap items-end justify-center gap-x-14 gap-y-10">
            @foreach ($communities as $community)
                <div class="flex flex-col items-center gap-3">
                    <img src="/images/partners/{{ $community['img'] }}.png" alt="{{ $community['label'] }}"
                        class="block h-14 w-auto object-contain brightness-0 invert">
                    <span class="text-[11.5px] font-semibold text-gray-500 uppercase tracking-wide text-center">{{ $community['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         ACCREDITATIONS
    ======================================================== --}}
<section class="bg-[#111111] py-16 border-t border-white/10">
    <div class="mx-auto px-8 text-center" style="max-width:1280px;">
        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">Accreditations</p>
        <h2 class="text-3xl font-bold text-white mb-10">Recognized By</h2>

        <div class="flex flex-wrap items-end justify-center gap-x-14 gap-y-10">
            <div class="flex flex-col items-center gap-3">
                <div class="flex items-center gap-4">
                    <img src="/images/partners/utmb-1.png" alt="UTMB Index" class="block h-[26px] w-auto">
                    <img src="/images/partners/utmb-2.png" alt="" class="block h-[26px] w-auto">
                    <img src="/images/partners/utmb-3.png" alt="" class="block h-[26px] w-auto">
                </div>
                <span class="text-[11.5px] font-semibold text-gray-500 uppercase tracking-wide">UTMB Index</span>
            </div>
            <div class="flex flex-col items-center gap-3">
                <img src="/images/partners/itra.png" alt="ITRA Index" class="block h-14 w-auto brightness-0 invert">
                <span class="text-[11.5px] font-semibold text-gray-500 uppercase tracking-wide">ITRA Index</span>
            </div>
            <div class="flex flex-col items-center gap-3">
                <img src="/images/partners/philtra.png" alt="PhilTra" class="block h-[30px] w-auto brightness-0 invert">
                <span class="text-[11.5px] font-semibold text-gray-500 uppercase tracking-wide">PhilTra</span>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
         PARTNER WITH US CTA
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24 text-center border-t border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold mb-2 uppercase tracking-wider">Join Us</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Partner With Us</h2>
        <p class="text-gray-400 max-w-xl mx-auto leading-relaxed mb-8">
            From gear to gel, hospitality to hydration, we build TGC100 alongside brands who believe in what the Cordilleras have to offer. With more races on the calendar ahead, there's always a new trail to build together. If that's you, let's talk.
        </p>
        <a href="mailto:partnership@ricon.ph" class="inline-flex py-3 px-6 items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
            Partner With Us
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
            partnership@ricon.ph
        </a>
    </div>
</section>

@endsection

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

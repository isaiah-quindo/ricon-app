@extends('layouts.public')
@section('title', 'The Great Cordillera 100')
@section('og_title', 'RICON — The Great Cordillera 100 Ultra Trail')
@section('og_description', 'Experience the ultimate mountain challenge. Choose from 10 KM, 21 KM, 60 KM, or 100 KM distances through the breathtaking Cordillera mountains. Register now.')

@section('content')
{{-- ========================================================
         HERO Section
    ======================================================== --}}
<section x-data="{ offset: 0 }" @scroll.window="offset = window.scrollY * 0.4" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-0">
    {{-- Background image with parallax --}}
    <div class="absolute left-0 right-0 bg-gray-800 select-none"
        style="top: -25%; height: 150%; will-change: transform;"
        :style="`transform: translateY(${offset}px)`">
        <img src="/hero-bg.png" class="w-full h-lvh object-cover" />
    </div>
    <!-- {{-- Dark overlay --}}
        <div class="absolute inset-0 bg-black/55"></div> -->

    <div class="relative z-10 text-center px-8 w-full" style="max-width:1280px; margin:2rem auto;">
        {{-- Event logo placeholder --}}
        <div data-hero-item class="mx-auto mb-20 w-48 h-32 flex items-center justify-center text-gray-500 text-xs select-none">
            <img src="/tgc100-logo.png" alt="The Greact Cordillera 100" />
        </div>

        <h1 data-hero-item class="max-w-[600px] mx-auto text-2xl md:text-4xl lg:text-4xl font-black leading-tight text-white mb-5">
            The Mountain Will Test You.
            The Journey Will Change You.
        </h1>

        <p data-hero-item class="text-white text-lg/6 max-w-2xl mx-auto mb-8">
            A 100KM ultra trail across the rugged beauty of Benguet and the untamed Cordillera mountains,
            where endurance meets breathtaking landscapes.
        </p>

        <div data-hero-item class="m-auto max-w-[600px] mb-8 grid grid-cols-1 md:grid-cols-2 gap-4 flex place-items-center">
            <a href="https://utmb.world/utmb-index" target="_blank">
                <img src="/images/utmb-index.png" class="w-[120px]" alt="UTMB Index" />
            </a>
            <img src="/images/itra-logo-dark.svg" class="w-20" alt="ITRA" />
        </div>

        <a data-hero-item href="#race-categories" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-primary-foreground hover:bg-orange-700 focus:outline-hidden focus:bg-primary-focus  disabled:opacity-50 disabled:pointer-events-none">
            Choose your Adventure
        </a>
    </div>
</section>


{{-- ========================================================
         WELCOME / COUNTDOWN
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24 text-center">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <h2 data-reveal class="text-3xl md:text-4xl font-bold text-white mb-4">
            Welcome to<br>The Great Cordillera 100
        </h2>
        <p data-reveal class="text-gray-400 max-w-xl mx-auto mb-12 leading-relaxed">
            Traverse the scenic trails of Benguet and the wild Cordillera mountainscape, a journey through living heritage across Luzon's highland spine. Pine-canopied ridgelines, river crossings, and steep ascents connect Baguio City to the municipalities of Benguet, demanding both strength and respect.
        </p>

        <p data-reveal class="text-gray-400 text-sm mb-2">Race day in</p>
        @php
        $daysLeft = max(0, (int) now()->diffInDays(\Carbon\Carbon::parse('2026-11-13'), false));
        @endphp
        <p data-reveal class="text-6xl md:text-7xl font-black text-white">{{ $daysLeft }} days</p>
    </div>
</section>

{{-- ========================================================
         Video SECTION
    ======================================================== --}}
<section id="about" class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">A stage for our land and our people</h2>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    We didn't just need another race. We needed a stage to showcase the natural terrain,
                    the beautiful trails, and the spirit this country has to offer. The Great Cordillera 100
                    is that stage.
                </p>
                <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-primary-foreground hover:bg-orange-700 focus:outline-hidden focus:bg-primary-focus  disabled:opacity-50 disabled:pointer-events-none">
                    Register
                </a>
            </div>
            <div class="rounded-2xl h-72 flex items-center justify-center text-gray-500 text-sm select-none">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/RyBZFPklZp8?si=iJHiZaDKyz7VLR3H" title="YouTube video player" frameborder="0" allow="autoplay;" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>


{{-- ========================================================
         RACE CATEGORIES
    ======================================================== --}}
<section id="race-categories" class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">

        <p data-reveal class="text-orange-500 text-sm font-semibold mb-2 uppercase tracking-wider">Race Categories</p>
        <h2 data-reveal class="text-3xl md:text-4xl font-bold text-white mb-3">Distances to suit every ability</h2>
        <p data-reveal class="text-gray-400 mb-10 max-w-xl">
            From 10KM to 100KM, there's a distance for every trail runner ready to take on the Cordillera.
        </p>

        {{-- 100 KM — Featured card --}}
        <div data-race-card class="rounded-xl overflow-hidden mb-6 grid grid-cols-1 md:grid-cols-4">
            {{-- Image --}}
            <div class="relative w-full col-span-1 md:col-span-3 bg-gray-700 flex items-center justify-center text-gray-500 text-xs select-none flex-shrink-0 min-h-80">
                <img src="/images/100km-bg.png" alt="100km Category" class="absolute inset-0 w-full h-full object-cover" />
                {{-- Dark gradient overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                <div class="absolute grid grid-cols-1 bottom-6 left-4 md:left-6">
                    <p class="text-white font-black text-3xl leading-none">
                        TGC <span class="text-orange-500">100 KM</span>
                    </p>
                    <span class="block p-1 my-2 justify-self-start bg-white/50">
                        <a href="https://utmb.world/utmb-index" target="_blank">
                            <img src="images/index-100M.png" class="w-[100px]" alt="UTMB Index 100M"/>
                        </a>
                    </span>
                    <p class="text-gray-300 text-sm mt-1">November 13, 2026</p>
                </div>
            </div>
            {{-- Stats --}}
            <div class="w-full col-span-1 md:col-span-1 bg-[#1a1a1a] p-8 flex flex-col justify-between">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Distance</p>
                        <p class="text-white font-bold text-xl">102.48 KM</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                        <p class="text-white font-bold text-xl">6124M D+</p>
                    </div>
                </div>
                <a href="{{ route('race-category.100km') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-6 py-3 rounded-lg text-center transition-colors block">
                    Race Details
                </a>
            </div>
        </div>

        {{-- 60 / 21 / 10 KM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 60 KM --}}
            <div data-race-card class="rounded-xl overflow-hidden bg-[#1a1a1a] flex flex-1">
                <div class="relative w-1/2 bg-gray-700 h-auto flex items-center justify-center text-gray-500 text-xs select-none">
                    {{-- Image --}}
                    <img src="/images/60km-bg.png" alt="60km Category" class="absolute inset-0 w-full h-full object-cover" />
                    {{-- Dark gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                    <div class="absolute grid grid-cols-1 bottom-6 left-4 md:left-6">
                        <p class="text-white font-black text-3xl leading-none">
                            TGC <span class="text-red-500">60 KM</span>
                        </p>
                        <span class="block p-1 my-2 justify-self-start bg-white/50">
                            <a href="https://utmb.world/utmb-index" target="_blank">
                                <img src="images/index-100K.png" class="w-[100px] h-[30px]" alt="UTMB Index 100K"/>
                            </a>
                        </span>
                        <p class="text-gray-300 text-sm mt-1">November 14, 2026</p>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Distance</p>
                            <p class="text-white font-bold">61.34 KM</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                            <p class="text-white font-bold">3584M D+</p>
                        </div>
                    </div>
                    <a href="{{ route('race-category.60km') }}"
                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg text-center transition-colors block">
                        Race Details
                    </a>
                </div>
            </div>

            {{-- 21 KM + 10 KM stacked --}}
            <div class="flex flex-col gap-6">

                {{-- 21 KM --}}
                <div data-race-card class="rounded-xl overflow-hidden bg-[#1a1a1a] flex flex-1">
                    <div class="relative bg-gray-700 w-1/2 flex-shrink-0 flex items-center justify-center text-gray-500 text-xs select-none">
                        {{-- Image --}}
                        <img src="/images/21km-bg.png" alt="21km Category" class="absolute inset-0 w-full h-full object-cover" />
                        {{-- Dark gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                        <div class="absolute bottom-6 left-4 md:left-6 grid grid-cols-1 lg:grid-cols-2 gap-2">
                            <div class="relative">
                                <p class="text-white font-black text-2xl leading-none">
                                    TGC <span class="text-green-400">21 KM</span>
                                </p>
                                <p class="text-gray-300 text-sm mt-0.5">November 15, 2026</p>
                            </div>
                            <div class="relative grid grid-cols-1">
                                <span class="block p-1 flex align-center justify-self-start bg-white/50">
                                    <a href="https://utmb.world/utmb-index" target="_blank">
                                        <img src="images/index-20K.png" class="w-[100px] h-[30px]" alt="UTMB Index 20K"/>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Distance</p>
                                <p class="text-white font-bold text-sm">21.78 KM</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Elevation Gain</p>
                                <p class="text-white font-bold text-sm">1194M D+</p>
                            </div>
                        </div>
                        <a href="{{ route('race-category.21km') }}"
                            class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-3 py-2 rounded-lg text-center transition-colors block">
                            Race Details
                        </a>

                    </div>
                </div>

                {{-- 10 KM --}}
                <div data-race-card class="rounded-xl overflow-hidden bg-[#1a1a1a] flex flex-1">
                    <div class="relative bg-gray-700 w-1/2 flex-shrink-0 flex items-center justify-center text-gray-500 text-xs select-none">
                        {{-- Image --}}
                        <img src="/images/10km-bg.png" alt="10km Category" class="absolute inset-0 w-full h-full object-cover" />
                        {{-- Dark gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                        <div class="absolute bottom-6 left-4 md:left-6">
                            <p class="text-white font-black text-2xl leading-none">
                                TGC <span class="text-cyan-400">10 KM</span>
                            </p>
                            <p class="text-gray-300 text-sm mt-0.5">November 15, 2026</p>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Distance</p>
                                <p class="text-white font-bold text-sm">10.32 KM</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Elevation Gain</p>
                                <p class="text-white font-bold text-sm">364M D+</p>
                            </div>
                        </div>
                        <a href="{{ route('race-category.10km') }}"
                            class="bg-cyan-500 hover:bg-cyan-600 text-white text-xs font-semibold px-3 py-2 rounded-lg text-center transition-colors block">
                            Race Details
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>


{{-- ========================================================
         DTP TRAIL × TGC 100 INTERNATIONAL COLLABORATION
    ======================================================== --}}
<section id="dtp-collab" class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16 items-center mb-12">
            <div class="rounded-2xl overflow-hidden">
                <img src="/images/dtp-tgc-collab.png" alt="DTP Trail × TGC 100 International Collaboration" class="w-full h-auto block" />
            </div>
            <div>
                <p class="text-orange-500 text-sm font-semibold mb-2 uppercase tracking-wider">International Collaboration</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">DTP Trail × TGC 100</h2>
                <p class="text-gray-400 leading-relaxed">
                    The DTP Trail (Taiwan) and The Great Cordillera 100 (Philippines) proudly announce a landmark
                    partnership that unites two trail races in celebration of endurance, community, and international
                    friendship. This collaboration is designed to uplift runners, strengthen communities, and build
                    bridges across borders.
                </p>
            </div>
        </div>

        {{-- Perks grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            {{-- Elite Exchange --}}
            <div class="bg-[#1a1a1a] rounded-xl p-8 flex flex-col">
                <div class="w-12 h-12 rounded-lg bg-orange-500/10 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.006 0H9.497m5.006 0a4.5 4.5 0 0 0 1.387-2.165c.232-.652.226-1.358-.001-2.018a4.5 4.5 0 0 0-1.388-2.166m-5.005 6.348a4.5 4.5 0 0 1-1.387-2.165 4.49 4.49 0 0 1 .001-2.018A4.5 4.5 0 0 1 9.497 8.4m5.006 0a4.49 4.49 0 0 0 1.39-2.02 4.49 4.49 0 0 0 0-2.02m-1.39 4.04L12 12l-2.503-3.6m5.006 0a4.49 4.49 0 0 1-2.503.756 4.49 4.49 0 0 1-2.503-.756m0 0a4.49 4.49 0 0 0-1.388-2.02 4.49 4.49 0 0 0 0-2.02" />
                    </svg>
                </div>
                <h3 class="text-white text-xl font-bold mb-3">Elite Exchange</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Male and female champions of the TGC 100K and 60K categories will be awarded sponsored
                    entry slots to the partner race.
                </p>
            </div>

            {{-- International Perks --}}
            <div class="bg-[#1a1a1a] rounded-xl p-8 flex flex-col">
                <div class="w-12 h-12 rounded-lg bg-orange-500/10 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <h3 class="text-white text-xl font-bold mb-3">International Perks</h3>
                <ul class="text-gray-400 text-sm leading-relaxed space-y-2 mb-4">
                    <li class="flex gap-2">
                        <span class="text-orange-500">•</span>
                        <span><span class="text-white font-semibold">15% Registration Discount</span> for Filipino runners.</span>
                    </li>
                </ul>
                <p class="text-gray-500 text-xs mt-auto">
                    Contact <a href="mailto:info@ricon.ph" class="text-orange-500 hover:text-orange-400">info@ricon.ph</a> for registration assistance.
                </p>
            </div>

            {{-- Migrant Worker Support --}}
            <div class="bg-[#1a1a1a] rounded-xl p-8 flex flex-col">
                <div class="w-12 h-12 rounded-lg bg-orange-500/10 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
                <h3 class="text-white text-xl font-bold mb-3">Migrant Worker Support</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Outstanding Filipino migrant workers in Taiwan who excel in the 100K, 74K, or 43K
                    categories at DTP Trail 2027 will be granted sponsored entry slots (male &amp; female)
                    to compete in selected RiCON races, enabling them to proudly return home and race in
                    the Cordilleras.
                </p>
            </div>

        </div>

        {{-- DTP Trail 2027 Details --}}
        <div class="bg-[#1a1a1a] rounded-xl p-8 md:p-10 mb-6">
            <h3 class="text-white text-2xl font-bold mb-6">DTP Trail 2027 Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Dates</p>
                    <p class="text-white font-semibold">January 9–10, 2027</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Location</p>
                    <p class="text-white font-semibold">Nanzhuang Township, Miaoli County, Taiwan</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Certification</p>
                    <p class="text-white font-semibold">ITRA recognized, UTMB® Index race</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Race Categories</p>
                    <p class="text-white font-semibold">100K, 74K, 43K, 26K, 16K, 9K</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Registration Period</p>
                    <p class="text-white font-semibold">May 1 – July 31, 2026</p>
                </div>
            </div>
        </div>

        {{-- Important Note --}}
        <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            <p class="text-gray-300 text-sm leading-relaxed">
                <span class="text-orange-400 font-semibold">Important Note:</span>
                All sponsored slots cover registration fees only. Airfare, transportation, and accommodation
                are not included.
            </p>
        </div>

    </div>
</section>


{{-- ========================================================
         FOOTER
    ======================================================== --}}

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (!window.gsap) return;

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) return;

        const { gsap, ScrollTrigger } = window;

        // Hero: staggered fade-up on load
        gsap.from('[data-hero-item]', {
            y: 24,
            opacity: 0,
            duration: 0.9,
            ease: 'power3.out',
            stagger: 0.12,
            delay: 0.1,
        });

        // Generic reveal on scroll
        gsap.utils.toArray('[data-reveal]').forEach((el) => {
            gsap.from(el, {
                y: 30,
                opacity: 0,
                duration: 0.8,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                },
            });
        });

        // Race category cards: staggered reveal
        gsap.utils.toArray('[data-race-card]').forEach((card, i) => {
            gsap.from(card, {
                y: 40,
                opacity: 0,
                duration: 0.9,
                ease: 'power3.out',
                delay: i * 0.08,
                scrollTrigger: {
                    trigger: card,
                    start: 'top 88%',
                    toggleActions: 'play none none none',
                },
            });
        });
    });
</script>
@endpush
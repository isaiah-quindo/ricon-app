@extends('layouts.public')
@section('title', 'The Great Cordillera 100')
@section('og_title', 'RICON — The Great Cordillera 100 Ultra Trail')
@section('og_description', 'Experience the ultimate mountain challenge. Choose from 10 KM, 21 KM, 60 KM, or 100 KM distances through the breathtaking Cordillera mountains. Register now.')

@section('content')
{{-- ========================================================
         HERO
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
        <div class="mx-auto mb-20 w-48 h-32 flex items-center justify-center text-gray-500 text-xs select-none">
            <img src="/tgc100-logo.png" alt="The Greact Cordillera 100" />
        </div>

        <h1 class="max-w-[600px] mx-auto text-2xl md:text-4xl lg:text-4xl font-black leading-tight text-white mb-5">
            The Mountain Will Test You.
            The Journey Will Change You.
        </h1>

        <p class="text-white text-lg/6 max-w-2xl mx-auto mb-8">
            A 100KM ultra trail across the rugged beauty of Benguet and the untamed Cordillera mountains,
            where endurance meets breathtaking landscapes.
        </p>

        <div class="m-auto max-w-[600px] mb-8 grid grid-cols-1 md:grid-cols-2 gap-4 flex place-items-center">
            <a href="https://utmb.world/utmb-index" target="_blank">
                <img src="/images/utmb-index.png" alt="UTMB Index" />
            </a>
            <img src="/images/itra-logo-dark.svg" class="w-20" alt="ITRA" />
        </div>

        <a href="#race-categories" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-primary-foreground hover:bg-orange-700 focus:outline-hidden focus:bg-primary-focus  disabled:opacity-50 disabled:pointer-events-none">
            Choose your Adventure
        </a>
    </div>
</section>


{{-- ========================================================
         WELCOME / COUNTDOWN
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24 text-center">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Welcome to<br>The Great Cordillera 100
        </h2>
        <p class="text-gray-400 max-w-xl mx-auto mb-12 leading-relaxed">
            Traverse the scenic trails of Benguet and the wild Cordillera mountainscape, a journey through living heritage across Luzon's highland spine. Pine-canopied ridgelines, river crossings, and steep ascents connect Baguio City to the municipalities of Benguet, demanding both strength and respect.
        </p>

        <p class="text-gray-400 text-sm mb-2">Race day in</p>
        @php
        $daysLeft = max(0, (int) now()->diffInDays(\Carbon\Carbon::parse('2026-11-13'), false));
        @endphp
        <p class="text-6xl md:text-7xl font-black text-white">{{ $daysLeft }} days</p>
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

        <p class="text-orange-500 text-sm font-semibold mb-2 uppercase tracking-wider">Race Categories</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Distances to suit every ability</h2>
        <p class="text-gray-400 mb-10 max-w-xl">
            From 10KM to 100KM, there's a distance for every trail runner ready to take on the Cordillera.
        </p>

        {{-- 100 KM — Featured card --}}
        <div class="rounded-xl overflow-hidden mb-6 grid grid-cols-1 md:grid-cols-4">
            {{-- Image --}}
            <div class="relative w-full col-span-1 md:col-span-3 bg-gray-700 flex items-center justify-center text-gray-500 text-xs select-none flex-shrink-0 min-h-80">
                <img src="/images/100km-bg.png" alt="100km Category" class="absolute inset-0 w-full h-full object-cover" />
                {{-- Dark gradient overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                <div class="absolute bottom-6 left-4 md:left-6">
                    <p class="text-white font-black text-3xl leading-none">
                        TGC <span class="text-orange-500">100 KM</span>
                        <span class="block p-1 my-2 justify-self-start bg-white/50">
                            <a href="https://utmb.world/utmb-index" target="_blank">
                                <img src="images/index-100M.png" class="w-[100px]" alt="UTMB Index 100M"/>
                            </a>
                        </span>
                    </p>
                    <p class="text-gray-300 text-sm mt-1">November 13, 2026</p>
                </div>
            </div>
            {{-- Stats --}}
            <div class="w-full col-span-1 md:col-span-1 bg-[#1a1a1a] p-8 flex flex-col justify-between">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Distance</p>
                        <p class="text-white font-bold text-xl">100 KM</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                        <p class="text-white font-bold text-xl">7000M D+</p>
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
            <div class="rounded-xl overflow-hidden bg-[#1a1a1a] flex flex-1">
                <div class="relative w-1/2 bg-gray-700 h-auto flex items-center justify-center text-gray-500 text-xs select-none">
                    {{-- Image --}}
                    <img src="/images/60km-bg.png" alt="60km Category" class="absolute inset-0 w-full h-full object-cover" />
                    {{-- Dark gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                    <div class="absolute bottom-6 left-4 md:left-6">
                        <p class="text-white font-black text-3xl leading-none">
                            TGC <span class="text-red-500">60 KM</span>
                            <span class="block p-1 my-2 justify-self-start bg-white/50">
                                <a href="https://utmb.world/utmb-index" target="_blank">
                                    <img src="images/index-100K.png" class="w-[100px] h-[30px]" alt="UTMB Index 100K"/>
                                </a>
                            </span>
                        </p>
                        <p class="text-gray-300 text-sm mt-1">November 14, 2026</p>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Distance</p>
                            <p class="text-white font-bold">60 KM</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                            <p class="text-white font-bold">4200M D+</p>
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
                <div class="rounded-xl overflow-hidden bg-[#1a1a1a] flex flex-1">
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
                            <div class="relative">
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
                                <p class="text-white font-bold text-sm">21 KM</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Elevation Gain</p>
                                <p class="text-white font-bold text-sm">1300M D+</p>
                            </div>
                        </div>
                        <a href="{{ route('race-category.21km') }}"
                            class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-3 py-2 rounded-lg text-center transition-colors block">
                            Race Details
                        </a>

                    </div>
                </div>

                {{-- 10 KM --}}
                <div class="rounded-xl overflow-hidden bg-[#1a1a1a] flex flex-1">
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
                                <p class="text-white font-bold text-sm">10 KM</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Elevation Gain</p>
                                <p class="text-white font-bold text-sm">500M D+</p>
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
         FOOTER
    ======================================================== --}}

@endsection
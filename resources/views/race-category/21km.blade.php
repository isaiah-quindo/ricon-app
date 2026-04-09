@extends('layouts.public')
@section('title', 'TGC 21 KM')

@section('content')
    {{-- ========================================================
         HERO
    ======================================================== --}}
    <section class="relative min-h-[70vh] flex items-end overflow-hidden pt-16">
        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-600 text-sm select-none">
            [Race hero image]
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

        <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
            <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-3">
                TGC <span class="text-green-400">21 KM</span>
            </h1>
            <p class="text-gray-300 text-lg">November 14, 2026 &mdash; Benguet, Cordillera, Philippines</p>
        </div>
    </section>


    {{-- ========================================================
         STATS BAR
    ======================================================== --}}
    <div class="bg-[#111111] border-b border-white/10">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-white/10">
                <div class="py-8 px-6 first:pl-0">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Distance</p>
                    <p class="text-white font-black text-2xl">21 KM</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                    <p class="text-white font-black text-2xl">7,290 M+</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                    <p class="text-white font-black text-2xl">10 hrs</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Race Date</p>
                    <p class="text-white font-black text-2xl">Nov 14, 2026</p>
                </div>
            </div>
        </div>
    </div>


    {{-- ========================================================
         ABOUT THE RACE
    ======================================================== --}}
    <section class="bg-[#0d0d0d] py-24">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-3">About the Race</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Your First Taste of the Cordillera</h2>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        The TGC 21KM is the perfect entry point into the world of Cordillera trail running. Designed for trail runners looking to push beyond road races, this distance delivers an authentic mountain experience without the extreme demands of the longer distances.
                    </p>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        Runners will experience the same stunning landscapes and challenging terrain as the longer races, condensed into a fast and exhilarating 21-kilometer journey.
                    </p>
                    <p class="text-gray-400 leading-relaxed">
                        With a 10-hour cutoff, this race welcomes runners of all paces who are ready for their first mountain trail adventure.
                    </p>
                </div>
                <div class="bg-gray-700 rounded-2xl h-80 flex items-center justify-center text-gray-500 text-sm select-none">
                    [Race photo]
                </div>
            </div>
        </div>
    </section>


    {{-- ========================================================
         COURSE PROFILE
    ======================================================== --}}
    <section class="bg-[#111111] py-24">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-2">Course Profile</p>
            <h2 class="text-3xl font-bold text-white mb-10">Elevation & Route</h2>

            <div class="bg-gray-800 rounded-xl h-48 flex items-center justify-center text-gray-500 text-sm select-none mb-10">
                [Elevation profile chart]
            </div>

            <h3 class="text-xl font-bold text-white mb-6">Aid Stations</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([['AS1 — Km 8', 'Elevation: 1,400m', '8 KM'], ['AS2 — Km 15', 'Elevation: 1,800m', '15 KM'], ['Finish Line', 'Benguet, Philippines', '21 KM']] as [$name, $location, $km])
                <div class="bg-[#1a1a1a] rounded-xl p-5 border border-white/5">
                    <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">{{ $km }}</p>
                    <p class="text-white font-bold mb-1">{{ $name }}</p>
                    <p class="text-gray-500 text-sm">{{ $location }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ========================================================
         REQUIREMENTS
    ======================================================== --}}
    <section class="bg-[#0d0d0d] py-24">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <div>
                    <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-3">Requirements</p>
                    <h2 class="text-3xl font-bold text-white mb-8">Mandatory Gear</h2>
                    <ul class="space-y-3">
                        @foreach (['Trail running shoes', 'Hydration pack (minimum 1L)', 'Headlamp with extra batteries', 'First aid kit', 'Whistle', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                        <li class="flex items-center gap-3 text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-3">Qualifications</p>
                    <h2 class="text-3xl font-bold text-white mb-8">Who Can Join</h2>
                    <ul class="space-y-3">
                        @foreach (['Minimum age: 16 years old on race day', 'Open to all fitness levels', 'Valid government-issued ID', 'Liability waiver must be signed'] as $item)
                        <li class="flex items-center gap-3 text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>


    {{-- ========================================================
         CTA
    ======================================================== --}}
    <section class="bg-[#111111] py-24">
        <div class="mx-auto px-8 text-center" style="max-width:1280px;">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Start your trail journey here.</h2>
            <p class="text-gray-400 mb-8 max-w-xl mx-auto">
                Secure your slot now. Registration slots are limited.
            </p>
            <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                Register Now
            </a>
        </div>
    </section>


    {{-- ========================================================
         FOOTER
    ======================================================== --}}
    
@endsection

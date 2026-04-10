@extends('layouts.public')
@section('title', 'TGC 100 KM')

@section('content')
{{-- ========================================================
         HERO
    ======================================================== --}}
<section class="relative min-h-[58vh] flex items-end overflow-hidden pt-16">
    {{-- Background placeholder --}}
    <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-600 text-sm select-none">
        <img src="/images/100km-hero.png" alt="TGC100KM" class="w-full h-full object-cover" />
    </div>
    {{-- Dark gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

    <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
        <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-3">
            TGC <span class="text-orange-500">100 KM</span>
        </h1>
        <p class="text-gray-300 text-lg">Camp John Hay</p>
    </div>
</section>


{{-- ========================================================
         STATS BAR
    ======================================================== --}}
<div class="bg-orange-500 border-b border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-2 md:grid-cols-6 md:divide-x divide-white/20">
            <div class="py-8 px-6 first:pl-0">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Distance</p>
                <p class="text-white font-black text-2xl">100 KM</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                <p class="text-white font-black text-2xl">7,290 M+</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                <p class="text-white font-black text-2xl">36 hrs</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Race Date</p>
                <p class="text-white font-black text-2xl">Nov 13, 2026</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Slots</p>
                <p class="text-white font-black text-2xl">100</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Price</p>
                <p class="text-white font-black text-2xl">₱6,490</p>
            </div>
        </div>
    </div>
</div>


{{-- ========================================================
         Inclusions
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">Inclusions</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">What's included in your registration</h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    From the moment you register to the moment you finish, we've got you covered; gear, timing, fuel, and a medal to prove you earned it.
                </p>
                <ul class="grid grid-cols-2 gap-x-6 gap-y-2 mb-6">
                    @foreach(['Finisher medal', 'Finisher hoodie', 'Event shirt', 'Event socks', 'Event tote bag', 'Race bib', 'Chip timing', 'Post-race meal', 'Race day insurance', 'Event stickers'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="#" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-layer-line text-muted-foreground-1 hover:border-orange-500 hover:text-orange-500 focus:outline-hidden focus:border-orange-500 focus:text-orange-500  disabled:opacity-50 disabled:pointer-events-none">
                    View all inclusions
                </a>
            </div>
            <div class="bg-gray-700 rounded-2xl h-80 flex items-center justify-center text-gray-500 text-sm select-none">
                [Race photo]
            </div>
        </div>
    </div>
</section>

<section class="min-h-[95px] bg-[#274B35]" style="background-image: url('/images/pattern-1.svg'); background-repeat: repeat-x; background-size: auto 100%; background-position:center center;">

</section>


{{-- ========================================================
         Registration Fee
    ======================================================== --}}
<section class="bg-[#483E1C] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Registration Fee</p>
        <h2 class="text-3xl font-bold text-white mb-10">Secure your slot</h2>

        {{-- Registration Fee --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-orange-500 text-center">
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Super Early Bird</p>
                <p class="text-white font-bold text-2xl mb-1">₱6,490</p>
                <p class="text-gray-500 text-sm">April 15 - May 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Early Bird</p>
                <p class="text-white font-bold text-2xl mb-1">₱6,990</p>
                <p class="text-gray-500 text-sm">April 16 - Jun 15</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Regular</p>
                <p class="text-white font-bold text-2xl mb-1">₱7,900</p>
                <p class="text-gray-500 text-sm">Jun 16 - Aug 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱8,500</p>
                <p class="text-gray-500 text-sm">Aug 16 - Sep 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Super Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱9,500</p>
                <p class="text-gray-500 text-sm">Sep 16 - Sep 30</p>
            </div>
        </div>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            Register Now
        </a>
    </div>
</section>

<section class="min-h-[95px] bg-[#142A1F]" style="background-image: url('/images/pattern-2.svg'); background-repeat: repeat-x; background-position: center center;">

</section>


{{-- ========================================================
         REQUIREMENTS
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Mandatory Gear --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-3">Requirements</p>
                <h2 class="text-xl font-bold text-white mb-5">Mandatory Gear</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Trail running shoes', 'Hydration pack (minimum 1.5L)', 'Emergency space blanket', 'Headlamp with extra batteries', 'First aid kit', 'Whistle', 'Rain jacket', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry"
                    class="inline-flex items-center gap-1.5 text-sm text-orange-500 hover:text-orange-400 transition-colors font-medium">
                    View full gear & entry rules
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Entry Requirements --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-3">Qualifications</p>
                <h2 class="text-xl font-bold text-white mb-5">Who Can Join</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Minimum age: 20 years old on race day', 'Adequate trail running experience required', 'Valid government-issued ID', 'Medical clearance may be required', 'Liability waiver must be signed', 'Full payment of registration fee'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry"
                    class="inline-flex items-center gap-1.5 text-sm text-orange-500 hover:text-orange-400 transition-colors font-medium">
                    View full entry requirements
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>


{{-- ========================================================
         CTA
    ======================================================== --}}
<section class="bg-[#111111] py-24">
    <div class="mx-auto px-8 text-center" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to take on the 100?</h2>
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
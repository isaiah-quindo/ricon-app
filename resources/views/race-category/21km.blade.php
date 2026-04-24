@extends('layouts.public')
@section('title', 'TGC 21 KM')
@section('og_title', 'TGC 21 KM — The Great Cordillera Trail Run')
@section('og_description', 'Race 21 kilometers through the breathtaking Cordillera mountains at RICON. A challenging course for seasoned trail runners. Register and take on the adventure.')

@section('content')
{{-- ========================================================
         HERO
    ======================================================== --}}
<section class="relative min-h-[58vh] flex items-end overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-600 text-sm select-none">
        <img src="/images/21km-hero.png" alt="TGC21KM" class="w-full h-full object-cover" />
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
    <div class="relative grid grid-cols-1 z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
        <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-3">
            TGC <span class="text-green-400">21 KM</span>
        </h1>
        <span class="block p-1 my-2 justify-self-start bg-white/50">
            <a href="https://utmb.world/utmb-index" target="_blank">
                <img src="/images/index-20K.png" class="w-[150px]" alt="UTMB Index 20K"/>
            </a>
        </span>
        <p class="text-gray-300 text-lg">Province of Benguet</p>
    </div>
</section>


{{-- ========================================================
         STATS BAR
    ======================================================== --}}
<div class="bg-green-600 border-b border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-2 md:grid-cols-6 md:divide-x divide-white/20">
            <div class="py-8 px-6 first:pl-0">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Distance</p>
                <p class="text-white font-black text-2xl">21 KM</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-0">Est. Elevation Gain</p>
                <p class="text-white font-black text-2xl">1300M D+</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                <p class="text-white font-black text-2xl">8 hrs</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Race Date</p>
                <p class="text-white font-black text-2xl">Nov 15, 2026</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Gunstart</p>
                <p class="text-white font-black text-2xl">5 AM</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Start & Finish</p>
                <p class="text-white font-black text-2xl">Baguio City</p>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================
         ABOUT THE RACE
    ======================================================== --}}
<section class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-3">About the Race</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">The TGC 21 KM</h2>
                <p class="text-gray-400 leading-relaxed mb-4">A half-marathon distance that refuses to behave like one. Two significant climbs, one river ford, and a final descent that will ask everything of your quads. Finish this and you will know whether 50 is in your future.
                </p>
                <!-- <ul class="mt-4 space-y-2">
                    @foreach(['1,300 M+ elevation gain', 'Pine forests and scenic ridgelines', 'Aid stations along the route', 'Open to 18 and above'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul> -->
            </div>
            <div class="bg-gray-700 rounded-2xl h-80 flex items-center justify-center text-gray-500 text-sm select-none overflow-hidden">
                <img src="/images/race-photo.png" alt="Race Photo" class="w-full h-full object-cover" />
            </div>
        </div>
    </div>
</section>


{{-- ========================================================
         Inclusions
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 items-center">
            <div class="col-span-1 md:col-span-2">
                <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-3">Inclusions</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">What's included in your registration</h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    From the moment you register to the moment you finish, we've got you covered; gear, timing, fuel, and a medal to prove you earned it.
                </p>
                <ul class="grid grid-cols-2 gap-x-6 gap-y-2 mb-6">
                    @foreach(['Race bib', 'Timing Chip', 'Finisher medal', 'Event shirt', 'Event tote bag', 'Post-race meal', 'Race day insurance', 'Event stickers', 'Municipality Fees', 'Environmental Fees', 'Barangay Fees'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-700 rounded-2xl h-80 flex items-center justify-center text-gray-500 text-sm select-none overflow-hidden">
                <img src="/images/inclusions/21-1.png" alt="Inclusion 1" class="w-full h-full object-cover" />
            </div>
        </div>
    </div>
</section>

<section class="min-h-[95px] bg-[#1C3D20]" style="background-image: url('/images/pattern-1.svg'); background-repeat: repeat-x; background-size: auto 100%; background-position: center center;">
</section>


{{-- ========================================================
         Registration Fee
    ======================================================== --}}
<section class="bg-[#1C3D20] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-2">Registration Fee</p>
        <h2 class="text-3xl font-bold text-white mb-6">Secure your slot</h2>
        <p class="text-white leading-relaxed mb-6">
            Lock in your place on the start line before it sells out.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="relative bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border-green-500 border text-center">
                <p class="absolute top-3 right-3 text-green-400 text-xs">⚠ Limited slots</p>
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Super Early Bird</p>
                <p class="text-white font-bold text-2xl mb-1">₱1,990</p>
                <p class="text-gray-500 text-sm">April 15 - May 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Early Bird</p>
                <p class="text-white font-bold text-2xl mb-1">₱2,490</p>
                <p class="text-gray-500 text-sm">May 16 - Jun 15</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Regular</p>
                <p class="text-white font-bold text-2xl mb-1">₱3,000</p>
                <p class="text-gray-500 text-sm">Jun 16 - Aug 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱3,500</p>
                <p class="text-gray-500 text-sm">Aug 16 - Sep 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Super Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱4,000</p>
                <p class="text-gray-500 text-sm">Sep 16 - Sep 30</p>
            </div>
        </div>
        <p class="text-gray-300 text-xs leading-relaxed mb-6">
            Registration fees are subject to change without notice.
        </p>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            Register Now
        </a>
    </div>
</section>

<section class="min-h-[95px] bg-[#102A14]" style="background-image: url('/images/pattern-2.svg'); background-repeat: repeat-x; background-position: center center;">
</section>


{{-- ========================================================
         REQUIREMENTS
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-3">Requirements</p>
                <h2 class="text-xl font-bold text-white mb-5">Mandatory Gear</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Trail running shoes', 'Hydration pack (500ml)', 'Emergency blanket', 'Headlamp + extra batteries (fully charged)', 'First aid kit', 'Whistle', 'Windbreaker', 'Mobile phone (fully charged)', 'Race bib (provided)', 'Extra cash', 'Ziploc bag for your trash'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-green-400 hover:text-green-300 transition-colors font-medium">
                    View full gear & entry rules
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Recommended Gear --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-3">Recommended</p>
                <h2 class="text-xl font-bold text-white mb-5">Recommended Gear</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['*Utensils (cups, bowls and sporks)', 'Anti-chafing cream (vaseline, petroleum jelly)', 'Trekking poles', 'Ice banda', 'Sunscreen', 'Sun glasses', 'Insect repellent lotion', 'Cap or sun hat', 'Spare socks', 'Spare top in case of dropout', 'Headlamp + extra batteries', 'Spare batteries', 'Power bank'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <p class="text-gray-400 text-xs pb-4">* No disposable utensils will be provided at the aid station. Kindly make sure you have your own utensils to enjoy the different beverage and food items at the aid station.</p>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-green-400 hover:text-green-300 transition-colors font-medium">
                    View full gear & entry rules
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-3">Qualifications</p>
                <h2 class="text-xl font-bold text-white mb-5">Who Can Join</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Minimum age: 18 years old on race day', 'Valid government-issued ID', 'Medical clearance may be required', 'Liability waiver must be signed', 'Full payment of registration fee'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-green-400 hover:text-green-300 transition-colors font-medium">
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
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8 text-center" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to take on TGC 21?</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">
            Secure your slot now. Registration slots are limited.
        </p>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            Register Now
        </a>
    </div>
</section>

@endsection
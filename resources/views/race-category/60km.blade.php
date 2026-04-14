@extends('layouts.public')
@section('title', 'TGC 60 KM')
@section('og_title', 'TGC 60 KM — The Great Cordillera Ultra Trail')
@section('og_description', 'Push your limits on 60 kilometers of stunning mountain terrain at RICON — The Great Cordillera Ultra Trail. A true test of endurance. Register today.')

@section('content')
{{-- ========================================================
         HERO
    ======================================================== --}}
<section class="relative min-h-[58vh] flex items-end overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-600 text-sm select-none">
        <img src="/images/60km-hero.png" alt="TGC60KM" class="w-full h-full object-cover" />
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
    <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-red-500 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
        <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-3">
            TGC <span class="text-red-500">60 KM</span>
        </h1>
        <p class="text-gray-300 text-lg">Province of Benguet</p>
    </div>
</section>


{{-- ========================================================
         STATS BAR
    ======================================================== --}}
<div class="bg-red-500 border-b border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-2 md:grid-cols-6 md:divide-x divide-white/20">
            <div class="py-8 px-6 first:pl-0">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Distance</p>
                <p class="text-white font-black text-2xl">60 KM</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                <p class="text-white font-black text-2xl">4,200M D+</p>
                <span class="text-xs">Aprrox</span>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                <p class="text-white font-black text-2xl">18 hrs</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Race Date</p>
                <p class="text-white font-black text-2xl">Nov 14, 2026</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Gunstart</p>
                <p class="text-white font-black text-2xl">3 AM</p>
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
                <p class="text-red-500 text-sm font-semibold uppercase tracking-wider mb-3">About the Race</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">The TGC 60 KM</h2>
                <p class="text-gray-400 leading-relaxed mb-4">60 kilometers of Cordillera terrain that tests pacing discipline, nutrition strategy, and the willingness to keep moving when the path becomes unclear. This is not a training run with a race bib.</p>
                <!-- <ul class="mt-4 space-y-2">
                    @foreach(['Three major climbs', 'Night segment (early start)', 'Aid stations every 8–10 km', 'Open to 21 and above'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
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
                <p class="text-red-500 text-sm font-semibold uppercase tracking-wider mb-3">Inclusions</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">What's included in your registration</h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    From the moment you register to the moment you finish, we've got you covered; gear, timing, fuel, and a medal to prove you earned it.
                </p>
                <ul class="grid grid-cols-2 gap-x-6 gap-y-2 mb-6">
                    @foreach(['Race bib', 'Chip timing', 'Finisher medal', 'Finisher hoodie', 'Event shirt', 'Event tote bag', 'Aid Station', 'Post-race meal', 'Race day insurance', 'Event stickers', 'Municipality Fees', 'Environmental Fees', 'Barangay Fees'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="relative col-span-1 rounded-2xl h-80 overflow-hidden select-none" x-data="{ active: 0, images: ['/images/inclusions/60-1.png', '/images/inclusions/60-2.png'] }">
                <template x-for="(img, i) in images" :key="i">
                    <img :src="img" :alt="'Inclusion ' + (i + 1)"
                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                        :class="active === i ? 'opacity-100' : 'opacity-0'" />
                </template>

                {{-- Prev --}}
                <button @click="active = (active - 1 + images.length) % images.length"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 hover:bg-black/70 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Next --}}
                <button @click="active = (active + 1) % images.length"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 hover:bg-black/70 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                    <template x-for="(img, i) in images" :key="i">
                        <button @click="active = i"
                            class="w-1.5 h-1.5 rounded-full transition-colors"
                            :class="active === i ? 'bg-white' : 'bg-white/40'"></button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="min-h-[95px] bg-[#3D1C1C]" style="background-image: url('/images/pattern-1.svg'); background-repeat: repeat-x; background-size: auto 100%; background-position: center center;">
</section>


{{-- ========================================================
         Registration Fee
    ======================================================== --}}
<section class="bg-[#3D1C1C] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-red-500 text-sm font-semibold uppercase tracking-wider mb-2">Registration Fee</p>
        <h2 class="text-3xl font-bold text-white mb-6">Secure your slot</h2>
        <p class="text-white leading-relaxed mb-6">
            Lock in your place on the start line before it sells out.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="relative bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border-red-500 border text-center">
                <p class="absolute top-3 right-3 text-red-400 text-xs">⚠ Limited slots</p>
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-1">Super Early Bird</p>
                <p class="text-white font-bold text-2xl mb-1">₱4,490</p>
                <p class="text-gray-500 text-sm">April 15 - May 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-1">Early Bird</p>
                <p class="text-white font-bold text-2xl mb-1">₱4,990</p>
                <p class="text-gray-500 text-sm">April 16 - Jun 15</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-1">Regular</p>
                <p class="text-white font-bold text-2xl mb-1">₱6,000</p>
                <p class="text-gray-500 text-sm">Jun 16 - Aug 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-1">Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱6,500</p>
                <p class="text-gray-500 text-sm">Aug 16 - Sep 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-1">Super Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱7,500</p>
                <p class="text-gray-500 text-sm">Sep 16 - Sep 30</p>
            </div>
        </div>
        <p class="text-gray-300 text-xs leading-relaxed mb-6">
            Registration fees are subject to change without notice.
        </p>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-red-600 text-white hover:bg-red-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            Register Now
        </a>
    </div>
</section>

<section class="min-h-[95px] bg-[#2A1010]" style="background-image: url('/images/pattern-2.svg'); background-repeat: repeat-x; background-position: center center;">
</section>


{{-- ========================================================
         REQUIREMENTS
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-3">Requirements</p>
                <h2 class="text-xl font-bold text-white mb-5">Mandatory Gear</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Trail running shoes', 'Hydration pack (1.5 liters)', 'Emergency blanket', 'Headlamp + extra batteries (fully charged)', 'First aid kit', 'Whistle', 'Rain jacket with 10,000mm', 'Mobile phone (fully charged)', 'Race bib (provided)', 'Cash (₱1,000)', 'Ziploc bag for your trash', 'Trail food — minimum 1,000 kcal (250–300 kcal/hr until next aid station; e.g. 2–3 gels, 2–3 energy bars, or equivalent)'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-red-500 hover:text-red-400 transition-colors font-medium">
                    View full gear & entry rules
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Recommended Gear --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-3">Recommended</p>
                <h2 class="text-xl font-bold text-white mb-5">Recommended Gear</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Utensils (cups, bowls and sporks)', 'Anti-chafing cream (vaseline, petroleum jelly)', 'Trekking Poles', 'Ice Banda', 'Sunscreen', 'Sun glasses', 'Insect Repellent Lotion', 'Cap or sun hat', 'Spare socks', 'Spare top in case of dropout', 'Headlamp + extra batteries', 'Spare batteries', 'Power bank'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-red-500 hover:text-red-400 transition-colors font-medium">
                    View full gear & entry rules
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-red-500 text-xs font-semibold uppercase tracking-wider mb-3">Qualifications</p>
                <h2 class="text-xl font-bold text-white mb-5">Who Can Join</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Minimum age: 18 years old on race day', 'Adequate trail running experience required', 'Valid government-issued ID', 'Medical clearance may be required', 'Liability waiver must be signed', 'Full payment of registration fee'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-red-500 hover:text-red-400 transition-colors font-medium">
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
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to conquer TGC 60?</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">
            Secure your slot now. Registration slots are limited.
        </p>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-red-600 text-white hover:bg-red-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            Register Now
        </a>
    </div>
</section>

@endsection
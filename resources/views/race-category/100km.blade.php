<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TGC 100 KM — The Great Cordillera 100</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kufam:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0a0a0a] text-white font-sans antialiased">

    {{-- ========================================================
         NAVIGATION
    ======================================================== --}}
    <nav x-data="{ scrolled: false }"
        x-on:scroll.window="scrolled = window.scrollY > 50"
        :class="scrolled ? 'bg-black/90 backdrop-blur-sm border-white/10' : 'bg-transparent border-transparent'"
        class="fixed top-0 left-0 right-0 z-50 border-b transition-all duration-300">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <div class="flex items-center justify-between h-16">

                <a href="/" class="flex items-center gap-2">
                    <img src="/ricon-logo.svg" alt="Ricon">
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="/#race-categories" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Race Categories</a>
                    <a href="/about" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">About Us</a>
                </div>

                <a href="{{ route('registration.create') }}" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                    Register
                </a>
            </div>
        </div>
    </nav>


    {{-- ========================================================
         HERO
    ======================================================== --}}
    <section class="relative min-h-[58vh] flex items-end overflow-hidden pt-16">
        {{-- Background placeholder --}}
        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-600 text-sm select-none">
            <img src="/images/100km-hero.png" alt="TGC100KM" />
        </div>
        {{-- Dark gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

        <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
            <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-3">
                TGC <span class="text-orange-500">100 KM</span>
            </h1>
            <p class="text-gray-300 text-lg">November 13, 2026 &mdash; Benguet, Cordillera, Philippines</p>
        </div>
    </section>


    {{-- ========================================================
         STATS BAR
    ======================================================== --}}
    <div class="bg-orange-500 border-b border-white/10">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-white/20">
                <div class="py-8 px-6 first:pl-0">
                    <p class="text-white text-xs uppercase tracking-wider mb-1">Distance</p>
                    <p class="text-white font-black text-2xl">100 KM</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-white text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                    <p class="text-white font-black text-2xl">7,290 M+</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-white text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                    <p class="text-white font-black text-2xl">36 hrs</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-white text-xs uppercase tracking-wider mb-1">Race Date</p>
                    <p class="text-white font-black text-2xl">Nov 13, 2026</p>
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
                    <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">About the Race</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">The TGC 100 KM Challenge</h2>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        The TGC 100KM is the flagship race of The Great Cordillera 100, a grueling 100-kilometer ultra trail that takes runners through the most challenging and breathtaking terrain the Cordillera mountains have to offer.
                    </p>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        Traversing the scenic trails of Benguet, runners will experience dramatic elevation changes, dense pine forests, and sweeping views of the mountain ranges that define northern Luzon.
                    </p>
                    <p class="text-gray-400 leading-relaxed">
                        With 7,290 meters of positive elevation gain and a 36-hour cutoff, this race is designed to test the limits of even the most experienced ultra runners.
                    </p>
                </div>
                <div class="bg-gray-700 rounded-2xl h-80 flex items-center justify-center text-gray-500 text-sm select-none">
                    [Race photo]
                </div>
            </div>
        </div>
    </section>


    {{-- ========================================================
         Registration Fee
    ======================================================== --}}
    <section class="bg-[#111111] py-24">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Fee</p>
            <h2 class="text-3xl font-bold text-white mb-10">Registration Fee</h2>

            {{-- Registration Fee --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-[#1a1a1a] rounded-xl p-5 border border-white/5 text-center border border-orange-500">
                    <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Super Early Bird</p>
                    <p class="text-white font-bold text-2xl mb-1">₱6,490</p>
                    <p class="text-gray-500 text-sm">April 15 - May 15</p>
                </div>
                <div class="bg-[#1a1a1a] rounded-xl p-5 border border-white/5 text-center">
                    <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Early Bird</p>
                    <p class="text-white font-bold text-2xl mb-1">₱6,990</p>
                    <p class="text-gray-500 text-sm">April 16 - Jun 15</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-[#1a1a1a] rounded-xl p-5 border border-white/5 text-center">
                    <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Regular</p>
                    <p class="text-white font-bold text-2xl mb-1">₱7,900</p>
                    <p class="text-gray-500 text-sm">Jun 16 - Aug 15</p>
                </div>
                <div class="bg-[#1a1a1a] rounded-xl p-5 border border-white/5 text-center">
                    <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Late</p>
                    <p class="text-white font-bold text-2xl mb-1">₱8,500</p>
                    <p class="text-gray-500 text-sm">Aug 16 - Sep 15</p>
                </div>
                <div class="bg-[#1a1a1a] rounded-xl p-5 border border-white/5 text-center">
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


    {{-- ========================================================
         REQUIREMENTS
    ======================================================== --}}
    <section class="bg-[#0d0d0d] py-24">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <div>
                    <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">Requirements</p>
                    <h2 class="text-3xl font-bold text-white mb-8">Mandatory Gear</h2>
                    <ul class="space-y-1">
                        @foreach (['Trail running shoes', 'Hydration pack (minimum 1.5L)', 'Emergency space blanket', 'Headlamp with extra batteries', 'First aid kit', 'Whistle', 'Rain jacket', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                        <li class="flex items-center gap-3 text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">Qualifications</p>
                    <h2 class="text-3xl font-bold text-white mb-8">Who Can Join</h2>
                    <ul class="space-y-1">
                        @foreach (['Minimum age: 18 years old on race day', 'Completion of at least one 50KM+ race', 'Valid government-issued ID', 'Medical clearance required', 'Liability waiver must be signed'] as $item)
                        <li class="flex items-center gap-3 text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
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
    <footer class="bg-black border-t border-white/10 py-8">
        <div class="mx-auto px-8 flex flex-wrap items-center justify-between gap-4" style="max-width:1280px;">
            <a href="/" class="flex items-center gap-2">
                <img src="/ricon-logo.svg" alt="Ricon">
            </a>
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-400">
                <a href="/#race-categories" class="hover:text-white transition-colors">Race Categories</a>
                <a href="/about" class="hover:text-white transition-colors">About</a>
            </div>
            <p class="text-gray-500 text-sm">© 2026 RICON</p>
        </div>
    </footer>

</body>

</html>
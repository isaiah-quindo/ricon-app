<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TGC 10 KM — The Great Cordillera 100</title>
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
    <section class="relative min-h-[70vh] flex items-end overflow-hidden pt-16">
        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-600 text-sm select-none">
            [Race hero image]
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

        <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
            <p class="text-cyan-400 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-3">
                TGC <span class="text-cyan-400">10 KM</span>
            </h1>
            <p class="text-gray-300 text-lg">November 15, 2026 &mdash; Benguet, Cordillera, Philippines</p>
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
                    <p class="text-white font-black text-2xl">10 KM</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Elevation Gain</p>
                    <p class="text-white font-black text-2xl">7,290 M+</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                    <p class="text-white font-black text-2xl">5 hrs</p>
                </div>
                <div class="py-8 px-6">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Race Date</p>
                    <p class="text-white font-black text-2xl">Nov 15, 2026</p>
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
                    <p class="text-cyan-400 text-sm font-semibold uppercase tracking-wider mb-3">About the Race</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Where Every Runner Belongs</h2>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        The TGC 10KM is the most accessible distance at The Great Cordillera 100, opening the door to mountain trail running for beginners, casual runners, and families who want to experience the beauty of the Cordillera.
                    </p>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        Despite the shorter distance, the course still delivers the signature Cordillera experience — scenic mountain views, fresh pine-scented air, and the thrill of running trails carved into the Philippine highlands.
                    </p>
                    <p class="text-gray-400 leading-relaxed">
                        Whether you're a first-time trail runner or a seasoned road racer looking for something new, the 10KM is your gateway to the mountains.
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
            <p class="text-cyan-400 text-sm font-semibold uppercase tracking-wider mb-2">Course Profile</p>
            <h2 class="text-3xl font-bold text-white mb-10">Elevation & Route</h2>

            <div class="bg-gray-800 rounded-xl h-48 flex items-center justify-center text-gray-500 text-sm select-none mb-10">
                [Elevation profile chart]
            </div>

            <h3 class="text-xl font-bold text-white mb-6">Aid Stations</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([['AS1 — Km 5', 'Elevation: 1,200m', '5 KM'], ['Finish Line', 'Benguet, Philippines', '10 KM']] as [$name, $location, $km])
                <div class="bg-[#1a1a1a] rounded-xl p-5 border border-white/5">
                    <p class="text-cyan-400 text-xs font-semibold uppercase tracking-wider mb-1">{{ $km }}</p>
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
                    <p class="text-cyan-400 text-sm font-semibold uppercase tracking-wider mb-3">Requirements</p>
                    <h2 class="text-3xl font-bold text-white mb-8">Mandatory Gear</h2>
                    <ul class="space-y-3">
                        @foreach (['Trail running or athletic shoes', 'Water bottle or hydration pack (min 500ml)', 'Whistle', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                        <li class="flex items-center gap-3 text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-cyan-400 text-sm font-semibold uppercase tracking-wider mb-3">Qualifications</p>
                    <h2 class="text-3xl font-bold text-white mb-8">Who Can Join</h2>
                    <ul class="space-y-3">
                        @foreach (['Minimum age: 14 years old on race day (under 18 with parental consent)', 'Open to all fitness levels', 'Valid ID required', 'Liability waiver must be signed'] as $item)
                        <li class="flex items-center gap-3 text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 flex-shrink-0"></span>
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
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Take your first step into the mountains.</h2>
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

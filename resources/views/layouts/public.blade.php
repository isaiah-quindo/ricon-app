<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="/logomark.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — RICON</title>
    <!-- Facebook Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ secure_url(request()->getRequestUri()) }}">
    <meta property="og:site_name" content="RICON">
    <meta property="og:title" content="@yield('og_title', 'RICON — The Great Cordillera 100 Ultra Trail')">
    <meta property="og:description" content="@yield('og_description', 'Experience the ultimate mountain challenge at The Great Cordillera 100 Ultra Trail. Choose from 15 KM, 21 KM, 60 KM, or 100 KM distances through the breathtaking Cordillera mountains.')">
    <meta property="og:image" content="{{ secure_asset('images/facebook-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kufam:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body class="bg-[#0a0a0a] text-white font-sans antialiased">

    {{-- ======================================================== --}}
    {{-- NAVIGATION --}}
    {{-- ======================================================== --}}
    <nav x-data="{ scrolled: false, open: false }"
        x-on:scroll.window="scrolled = window.scrollY > 50"
        :class="scrolled || open ? 'bg-black/90 backdrop-blur-sm border-white/10' : 'bg-transparent border-transparent'"
        class="fixed top-0 left-0 right-0 z-50 border-b transition-all duration-300">
        <div class="mx-auto px-8" style="max-width:1280px;">
            <div class="flex items-center justify-between h-16">

                <a href="/" class="flex items-center gap-2">
                    <img src="/ricon-logo.svg" alt="Ricon">
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="/#race-categories" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Race Categories</a>
                    <a href="{{ route('rules') }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Rules</a>
                    <a href="/about" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">About Us</a>
                </div>

                <a href="{{ route('registration.create') }}" class="hidden md:inline-flex py-3 px-4 items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                    Register
                </a>

                {{-- Mobile hamburger --}}
                <button @click="open = !open" class="md:hidden flex items-center justify-center w-10 h-10 text-gray-300 hover:text-white transition-colors" aria-label="Toggle menu">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-white/10">
            <div class="mx-auto px-8 py-4 flex flex-col gap-4" style="max-width:1280px;">
                <a href="/#race-categories" @click="open = false" class="text-gray-300 hover:text-white text-sm font-medium transition-colors py-2">Race Categories</a>
                <a href="{{ route('rules') }}" @click="open = false" class="text-gray-300 hover:text-white text-sm font-medium transition-colors py-2">Rules</a>
                <a href="/about" @click="open = false" class="text-gray-300 hover:text-white text-sm font-medium transition-colors py-2">About Us</a>
                <a href="{{ route('registration.create') }}" class="mt-2 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden">
                    Register
                </a>
            </div>
        </div>
    </nav>

    {{-- ======================================================== --}}
    {{-- PAGE CONTENT --}}
    {{-- ======================================================== --}}
    @yield('content')

    {{-- ======================================================== --}}
    {{-- FOOTER --}}
    {{-- ======================================================== --}}
    <footer class="bg-black border-t border-white/10 py-8">
        <div class="mx-auto px-8 flex flex-wrap items-center justify-between gap-4" style="max-width:1280px;">
            <a href="/" class="flex items-center gap-2">
                <img src="/ricon-logo.svg" alt="Ricon">
            </a>
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-400">
                <a href="/#race-categories" class="hover:text-white transition-colors">Race Categories</a>
                <a href="{{ route('rules') }}" class="hover:text-white transition-colors">Rules & Guidelines</a>
                <a href="/about" class="hover:text-white transition-colors">About</a>
            </div>
            <p class="text-gray-500 text-sm">© {{ date('Y') }} RICON</p>
        </div>
    </footer>

</body>

</html>
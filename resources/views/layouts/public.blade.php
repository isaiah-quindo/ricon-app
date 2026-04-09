<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="/logomark.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — RICON</title>
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
                    <a href="{{ route('rules') }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Rules</a>
                    <a href="/about" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">About Us</a>
                </div>

                <a href="{{ route('registration.create') }}" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
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

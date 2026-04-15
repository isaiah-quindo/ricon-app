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
    <meta property="og:description" content="@yield('og_description', 'Experience the ultimate mountain challenge at The Great Cordillera 100 Ultra Trail. Choose from 10 KM, 21 KM, 60 KM, or 100 KM distances through the breathtaking Cordillera mountains.')">
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
    <footer class="bg-black border-t border-white/10">
        {{-- Social media section --}}
        <div class="border-b border-white/10 py-12">
            <div class="mx-auto px-8 text-center" style="max-width:1280px;">
                <p class="text-gray-400 text-sm uppercase tracking-widest mb-6">Connect With Us</p>
                <div class="flex items-center justify-center gap-6">
                    <a href="https://www.facebook.com/profile.php?id=61585439769463" target="_blank" rel="noopener noreferrer"
                        class="flex items-center gap-3 text-gray-300 hover:text-white transition-colors group">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full border border-white/20 group-hover:border-white/50 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </span>
                        <span class="text-sm font-medium">Facebook</span>
                    </a>
                    <a href="https://www.instagram.com/ricon.race/" target="_blank" rel="noopener noreferrer"
                        class="flex items-center gap-3 text-gray-300 hover:text-white transition-colors group">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full border border-white/20 group-hover:border-white/50 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        <span class="text-sm font-medium">Instagram</span>
                    </a>
                </div>
            </div>
        </div>
        {{-- Bottom bar --}}
        <div class="py-8">
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
        </div>
    </footer>

</body>

</html>
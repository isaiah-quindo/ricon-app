<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Register') — The Great Cordillera 100 Ultra Trail</title>
    <!-- Facebook Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="RICON">
    <meta property="og:title" content="@yield('og_title', 'Register for RICON — The Great Cordillera 100 Ultra Trail')">
    <meta property="og:description" content="@yield('og_description', 'Sign up now for The Great Cordillera 100 Ultra Trail. Choose your distance: 10 KM, 21 KM, 60 KM, or 100 KM.')">
    <meta property="og:image" content="{{ asset('images/facebook-image.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">

    {{-- Hero header --}}
    <div class="bg-gray-900 text-white py-10 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 text-orange-400 text-sm font-semibold tracking-widest uppercase mb-5">
                <img src="/tgc-100-reg-logo.png" alt="The Great Cordillera 100 Ultra Trail" style="max-width: 350px; ">
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">@yield('heading', 'Race Registration')</h1>
            <p class="text-gray-400 text-base max-w-xl mx-auto">@yield('subheading')</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 sm:py-10">
        @yield('content')
    </div>

    <footer class="text-center py-8 text-xs text-gray-400">
        The Great Cordillera 100 Ultra Trail &copy; {{ date('Y') }}
    </footer>

    @stack('scripts')

</body>

</html>

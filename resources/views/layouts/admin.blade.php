<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="/logomark.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'Ricon') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">

    <!-- Top navbar -->
    <header class="bg-gray-900 border-b border-gray-800 mb-6">
        <div class="max-w-[1280px] mx-auto px-6 h-16 flex items-center justify-between gap-8">

            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 flex-shrink-0">
                <img src="/ricon-logo.svg" alt="Ricon">
            </a>

            <!-- Nav links -->
            <nav class="flex items-center gap-1 ml-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.registrations.index') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.registrations.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    Registrations
                </a>
                <a href="{{ route('admin.race-categories.index') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.race-categories.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    Race Categories
                </a>
            </nav>

            <!-- User + logout -->
            <div class="ml-auto flex items-center gap-3">
                <span class="text-sm text-gray-400">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-3 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- Flash messages -->
    @if(session('success') || session('error'))
    <div class="max-w-[1280px] mx-auto px-6 pt-4">
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-start gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif
    </div>
    @endif

    <!-- Page content -->
    <main class="max-w-[1280px] mx-auto px-6 pb-6">
        @yield('content')
    </main>

</body>

</html>
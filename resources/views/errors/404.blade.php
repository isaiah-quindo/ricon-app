@extends('layouts.public')

@section('title', 'Page Not Found')

@section('content')
    <section class="min-h-screen flex items-center justify-center bg-[#0a0a0a] px-8 pt-16">
        <div class="text-center max-w-xl">
            <p class="text-orange-500 font-extrabold text-7xl mb-6">404</p>
            <h1 class="text-white text-[2rem] font-bold mb-4">Page Not Found</h1>
            <p class="text-gray-400 text-base leading-relaxed mb-10">
                The trail you're looking for doesn't exist or has moved. Check the URL, or head back to the start line.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 mb-12">
                <a href="/" class="py-3 px-6 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden transition-colors">
                    Back to Home
                </a>
                <a href="/#race-categories" class="py-3 px-6 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-white/20 text-gray-300 hover:text-white hover:border-white/50 focus:outline-hidden transition-colors">
                    View Race Categories
                </a>
            </div>

            <p class="text-gray-500 text-sm">
                Having trouble? Contact us at
                <a href="mailto:info@ricon.ph" class="text-orange-500 hover:text-orange-400 transition-colors">info@ricon.ph</a>
                or message us on
                <a href="https://www.facebook.com/profile.php?id=61585439769463" target="_blank" rel="noopener noreferrer" class="text-orange-500 hover:text-orange-400 transition-colors">Facebook</a>.
            </p>
        </div>
    </section>
@endsection

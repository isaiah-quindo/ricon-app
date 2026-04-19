<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Submitted — {{ config('app.name', 'Ricon') }} Ultra Trail</title>
    <!-- Facebook Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="RICON">
    <meta property="og:title" content="Registration Submitted — The Great Cordillera 100 Ultra Trail">
    <meta property="og:description" content="Your registration for The Great Cordillera 100 Ultra Trail has been submitted. We will review your payment and confirm your spot soon.">
    <meta property="og:image" content="{{ asset('images/facebook-image.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-[1280px] mx-auto flex flex-col items-center">
        <div class="w-full max-w-md text-center">

            {{-- Success icon --}}
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">You're registered!</h1>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                Your registration has been submitted successfully. Our team will review your payment proof and notify you by email once your registration is confirmed.
            </p>

            {{-- Info card --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-left mb-6 space-y-3">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">What happens next?</h2>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                        <p class="text-sm text-gray-700">Our admin team reviews your proof of payment within 2-3 days.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                        <p class="text-sm text-gray-700">Once verified, your registration is approved and a bib number is assigned.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                        <p class="text-sm text-gray-700">You'll receive a confirmation email with your race details and bib number.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('registration.create') }}"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Register Another
                </a>
                <a href="{{ url('/') }}"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Back to Home
                </a>
            </div>

            <p class="text-xs text-gray-400 mt-8">
                The Great Cordillera 100 Ultra Trail &copy; {{ date('Y') }}
            </p>
        </div>
    </div>

</body>

</html>
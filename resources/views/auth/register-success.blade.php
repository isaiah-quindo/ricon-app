<x-guest-layout>
    <div class="text-center py-4">

        {{-- Icon --}}
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-xl font-bold text-gray-900 mb-2">Account Created!</h1>
        <p class="text-sm text-gray-500 leading-relaxed mb-6">
            Your account has been submitted for review.<br>
            An admin will confirm your registration.
        </p>

        {{-- Steps --}}
        <div class="text-left bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                <p class="text-sm text-gray-600">Admin reviews your account request.</p>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                <p class="text-sm text-gray-600">Once confirmed, you'll receive an email with access details.</p>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                <p class="text-sm text-gray-600">You can then log in and manage your registration.</p>
            </div>
        </div>

        <a href="{{ route('login') }}"
            class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
            Go to Login
        </a>

    </div>
</x-guest-layout>

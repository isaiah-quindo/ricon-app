<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — The Great Cordillera 100 Ultra Trail</title>
    <!-- Facebook Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="RICON">
    <meta property="og:title" content="Register for RICON — The Great Cordillera 100 Ultra Trail">
    <meta property="og:description" content="Sign up now for The Great Cordillera 100 Ultra Trail. Choose your distance — 10 KM, 21 KM, 60 KM, or 100 KM — and begin your mountain running journey.">
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
    <div class="bg-gray-900 text-white py-12 px-4">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 text-orange-400 text-sm font-semibold tracking-widest uppercase mb-6">
                <img src="/tgc-100-reg-logo.png" alt="The Great Cordillera 100 Ultra Trail" style="max-width: 350px; ">
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">Race Registration</h1>
            <p class="text-gray-400 text-base max-w-md mx-auto">
                Fill out the form below to register for the race. You will receive a confirmation once your payment is verified.
            </p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-10">

        <form method="POST" action="{{ route('registration.store') }}" enctype="multipart/form-data"
            x-data="registrationForm()" class="space-y-6">
            @csrf

            {{-- Validation errors summary --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-1">Please fix the following errors:</p>
                        <ul class="text-sm text-red-700 space-y-0.5 list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            {{-- Step 1: Race Category --}}
            <div x-show="!reviewing" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
                        Choose Your Race Category
                    </h2>
                </div>
                <div class="p-6">
                    @if($categories->isEmpty())
                    <p class="text-sm text-gray-500 text-center py-4">No race categories are currently available.</p>
                    @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($categories as $cat)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="race_category_id" value="{{ $cat->id }}"
                                x-model="race_category_id"
                                {{ old('race_category_id') === $cat->id ? 'checked' : '' }}
                                class="sr-only peer" required>
                            <div class="border-2 rounded-xl p-4 transition-all
                                    peer-checked:border-orange-600 peer-checked:bg-orange-50
                                    border-gray-200 hover:border-gray-300">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="text-lg font-bold text-gray-900">{{ $cat->name }}</span>
                                    <span class="text-lg font-bold text-orange-600 flex-shrink-0 pe-4">₱{{ number_format($cat->price, 0) }}</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 rounded px-2 py-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        {{ $cat->distance_km }} km
                                    </span>
                                    @if($cat->elevation_m)
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 rounded px-2 py-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7M12 3v18" />
                                        </svg>
                                        {{ $cat->elevation_m }}m elev.
                                    </span>
                                    @endif
                                </div>
                                <!-- @if($cat->description)
                                <p class="text-xs text-gray-400 mt-2">{{ $cat->description }}</p>
                                @endif -->
                            </div>
                            {{-- Check indicator --}}
                            <div class="absolute top-3 right-3 mt-2 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-orange-600 peer-checked:bg-orange-600 flex items-center justify-center transition-all hidden peer-checked:flex">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @endif
                    @error('race_category_id')
                    <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Step 2: Personal Information --}}
            <div x-show="!reviewing" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
                        Personal Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name"
                                x-model="first_name"
                                value="{{ old('first_name') }}" required autocomplete="given-name"
                                class="w-full rounded-lg border {{ $errors->has('first_name') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                            @error('first_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name"
                                x-model="last_name"
                                value="{{ old('last_name') }}" required autocomplete="family-name"
                                class="w-full rounded-lg border {{ $errors->has('last_name') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                            @error('last_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sex" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Sex <span class="text-red-500">*</span>
                            </label>
                            <select id="sex" name="sex" x-model="sex" required
                                class="w-full rounded-lg border {{ $errors->has('sex') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white">
                                <option value="">Select sex</option>
                                <option value="male" {{ old('sex') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('sex') === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Birthdate <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="birthdate" name="birthdate"
                                x-model="birthdate"
                                value="{{ old('birthdate') }}" required
                                class="w-full rounded-lg border {{ $errors->has('birthdate') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                            @error('birthdate')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email Address <span class="text-red-500">*</span><br>
                                <span class="text-xs">Street,</span>
                            </label>
                            <input type="email" id="email" name="email"
                                x-model="email"
                                value="{{ old('email') }}" required autocomplete="email"
                                class="w-full rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                            @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mobile Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="mobile_number" name="mobile_number"
                                x-model="mobile_number"
                                value="{{ old('mobile_number') }}" required autocomplete="tel"
                                placeholder="+63 9XX XXX XXXX"
                                class="w-full rounded-lg border {{ $errors->has('mobile_number') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                            @error('mobile_number')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Home Address <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="2" x-model="address" required autocomplete="street-address"
                                class="w-full rounded-lg border {{ $errors->has('address') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none">{{ old('address') }}</textarea>
                            @error('address')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="shirt_size" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Shirt Size <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <label class="cursor-pointer">
                                    <input type="radio" name="shirt_size" value="{{ $size }}"
                                        x-model="shirt_size"
                                        {{ old('shirt_size') === $size ? 'checked' : '' }}
                                        class="sr-only peer" required>
                                    <span class="inline-flex items-center justify-center w-12 h-10 border-2 rounded-lg text-sm font-semibold transition-all
                                             peer-checked:border-orange-600 peer-checked:bg-orange-600 peer-checked:text-white
                                             border-gray-200 text-gray-600 hover:border-gray-300">
                                        {{ $size }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @error('shirt_size')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Step 3: Emergency Contact --}}
            <div x-show="!reviewing" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</span>
                        Emergency Contact
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Contact Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                x-model="emergency_contact_name"
                                value="{{ old('emergency_contact_name') }}" required
                                class="w-full rounded-lg border {{ $errors->has('emergency_contact_name') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                            @error('emergency_contact_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="emergency_contact_number" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Contact Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="emergency_contact_number" name="emergency_contact_number"
                                x-model="emergency_contact_number"
                                value="{{ old('emergency_contact_number') }}" required
                                placeholder="+63 9XX XXX XXXX"
                                class="w-full rounded-lg border {{ $errors->has('emergency_contact_number') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                            @error('emergency_contact_number')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 4: Payment --}}
            <div x-show="!reviewing" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">4</span>
                        Payment
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 text-sm text-indigo-800">
                        <p class="font-semibold mb-1">Payment Instructions</p>
                        <p class="text-indigo-700">Please send your registration fee to the bank account below, then upload your proof of payment.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm">
                            <p class="font-semibold text-gray-800 mb-3">Bank Transfer</p>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-gray-500 text-xs">Bank</dt>
                                    <dd class="font-medium text-gray-800">Bank Transfer Rizal Commercial Banking Corporation (RCBC)</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 text-xs">Account Name</dt>
                                    <dd class="font-medium text-gray-800">RiCON</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 text-xs">Account Number</dt>
                                    <dd class="font-medium text-gray-800 tracking-wider">7-591-41115-4</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-center">
                            <p class="font-semibold text-gray-800 mb-3">Scan to Pay</p>
                            <img src="{{ asset('images/qr-code.png') }}" alt="Payment QR Code" class="mx-auto w-full max-w-[160px] object-contain rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Payment Method
                        </label>
                        <select id="payment_method" name="payment_method" x-model="payment_method"
                            class="w-full rounded-lg border border-gray-200 text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white">
                            <option value="">Select method</option>
                            <option value="GCash" {{ old('payment_method') === 'GCash' ? 'selected' : '' }}>GCash</option>
                            <option value="Maya" {{ old('payment_method') === 'Maya' ? 'selected' : '' }}>Maya</option>
                            <option value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Other" {{ old('payment_method') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="proof_of_payment" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Proof of Payment <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed rounded-xl p-6 text-center transition-colors {{ $errors->has('proof_of_payment') ? 'border-red-300 bg-red-50' : 'border-gray-200 hover:border-orange-300' }}">
                            <input type="file" id="proof_of_payment" name="proof_of_payment"
                                accept="image/jpeg,image/png"
                                required
                                @change="fileName = $event.target.files[0]?.name"
                                class="sr-only" />
                            <label for="proof_of_payment" class="cursor-pointer">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p x-show="!fileName" class="text-sm text-gray-500">
                                    <span class="font-medium text-orange-600">Click to upload</span> or drag and drop
                                </p>
                                <p x-show="fileName" class="text-sm font-medium text-orange-600" x-text="fileName"></p>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG — max 5MB</p>
                            </label>
                        </div>
                        @error('proof_of_payment')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Step 5: Agreements --}}
            <div x-show="!reviewing" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">5</span>
                        Agreements
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="waiver_agreed" value="1"
                            {{ old('waiver_agreed') ? 'checked' : '' }}
                            required
                            class="mt-0.5 w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500 flex-shrink-0">
                        <span class="text-sm text-gray-700">
                            I agree to the <span class="font-semibold text-gray-900"><a href="#" target="_blank" class="text-orange-500">Liability Waiver</a></span>. I understand that trail running involves risks and I voluntarily assume all risks associated with participation.
                        </span>
                    </label>
                    @error('waiver_agreed')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="terms_agreed" value="1"
                            {{ old('terms_agreed') ? 'checked' : '' }}
                            required
                            class="mt-0.5 w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500 flex-shrink-0">
                        <span class="text-sm text-gray-700">
                            I agree to the <span class="font-semibold text-gray-900"><a href="/rules" target="_blank" class="text-orange-500">Rules and Conditions</a></span> of this race, including the race rules, cutoff times, and disqualification policies.
                        </span>
                    </label>
                    @error('terms_agreed')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror

                </div>
            </div>

            {{-- Review Panel --}}
            <div x-show="reviewing" x-cloak class="space-y-4">

                {{-- Info banner --}}
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                    <p class="text-sm font-semibold text-orange-800">Please review your details before submitting.</p>
                    <p class="text-xs text-orange-600 mt-0.5">Click "Edit" to go back and make changes.</p>
                </div>

                {{-- 1. Race Category --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
                            Race Category
                        </h2>
                    </div>
                    <div class="p-6">
                        <template x-if="selectedCategory">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-base font-bold text-gray-900" x-text="selectedCategory.name"></p>
                                    <p class="text-sm text-gray-500" x-text="selectedCategory.distance_km + ' km'"></p>
                                </div>
                                <p class="text-lg font-bold text-orange-600" x-text="formattedPrice"></p>
                            </div>
                        </template>
                        <template x-if="!selectedCategory">
                            <p class="text-sm text-gray-400 italic">No category selected</p>
                        </template>
                    </div>
                </div>

                {{-- 2. Personal Information --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
                            Personal Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">First Name</dt>
                                <dd class="font-medium text-gray-800" x-text="first_name || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Last Name</dt>
                                <dd class="font-medium text-gray-800" x-text="last_name || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Sex</dt>
                                <dd class="font-medium text-gray-800" x-text="formattedSex"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Birthdate</dt>
                                <dd class="font-medium text-gray-800" x-text="formattedBirthdate"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Email</dt>
                                <dd class="font-medium text-gray-800 break-all" x-text="email || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Mobile</dt>
                                <dd class="font-medium text-gray-800" x-text="mobile_number || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Shirt Size</dt>
                                <dd class="font-medium text-gray-800" x-text="shirt_size || '—'"></dd>
                            </div>
                            <div class="col-span-2 sm:col-span-3">
                                <dt class="text-xs text-gray-400 mb-0.5">Address</dt>
                                <dd class="font-medium text-gray-800" x-text="address || '—'"></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- 3. Emergency Contact --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</span>
                            Emergency Contact
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Name</dt>
                                <dd class="font-medium text-gray-800" x-text="emergency_contact_name || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Number</dt>
                                <dd class="font-medium text-gray-800" x-text="emergency_contact_number || '—'"></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- 4. Payment --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">4</span>
                            Payment
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Payment Method</dt>
                                <dd class="font-medium text-gray-800" x-text="payment_method || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 mb-0.5">Proof of Payment</dt>
                                <dd class="font-medium text-gray-800" x-text="fileName || '—'"></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- 5. Agreements --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">5</span>
                            Agreements
                        </h2>
                    </div>
                    <div class="p-6 space-y-2 text-sm text-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Liability Waiver agreed
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Rules and Conditions agreed
                        </div>
                    </div>
                </div>

                {{-- Review actions --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <button type="button" @click="backToForm()"
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 active:scale-95 transition-all">
                        &larr; Edit
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-3.5 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-600/20">
                        Confirm &amp; Submit
                    </button>
                </div>

            </div>

            {{-- Review trigger (shown when filling form) --}}
            <div x-show="!reviewing" class="flex flex-col items-center gap-3">
                <button type="button" @click="showReview()"
                    class="w-full sm:w-auto px-8 py-3.5 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-600/20">
                    Review My Registration
                </button>
                <p class="text-xs text-gray-400 text-center max-w-sm">
                    Your registration will be reviewed and approved once payment is verified. You will be notified by email.
                </p>
            </div>

        </form>
    </div>

    <footer class="text-center py-8 text-xs text-gray-400">
        The Great Cordillera 100 Ultra Trail &copy; {{ date('Y') }}
    </footer>

    <script type="application/json" id="race-categories-data">
        {!! json_encode($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'distance_km' => $c->distance_km, 'price' => $c->price])->values()) !!}
    </script>

    <script>
        function registrationForm() {
            const categories = JSON.parse(document.getElementById('race-categories-data').textContent);

            return {
                reviewing: false,

                race_category_id: "{{ old('race_category_id', '') }}",
                first_name: "{{ old('first_name', '') }}",
                last_name: "{{ old('last_name', '') }}",
                sex: "{{ old('sex', '') }}",
                birthdate: "{{ old('birthdate', '') }}",
                email: "{{ old('email', '') }}",
                mobile_number: "{{ old('mobile_number', '') }}",
                address: "{{ old('address', '') }}",
                shirt_size: "{{ old('shirt_size', '') }}",
                emergency_contact_name: "{{ old('emergency_contact_name', '') }}",
                emergency_contact_number: "{{ old('emergency_contact_number', '') }}",
                payment_method: "{{ old('payment_method', '') }}",
                fileName: "",

                get selectedCategory() {
                    return categories.find(c => c.id === this.race_category_id) || null;
                },

                get formattedPrice() {
                    if (!this.selectedCategory) return "—";
                    return "₱" + Number(this.selectedCategory.price).toLocaleString("en-PH", {
                        minimumFractionDigits: 0
                    });
                },

                get formattedSex() {
                    return this.sex ? this.sex.charAt(0).toUpperCase() + this.sex.slice(1) : "—";
                },

                get formattedBirthdate() {
                    if (!this.birthdate) return "—";
                    const d = new Date(this.birthdate + "T00:00:00");
                    return d.toLocaleDateString("en-PH", {
                        year: "numeric",
                        month: "long",
                        day: "numeric"
                    });
                },

                showReview() {
                    this.reviewing = true;
                    window.scrollTo({ top: 0, behavior: "smooth" });
                },

                backToForm() {
                    this.reviewing = false;
                    window.scrollTo({ top: 0, behavior: "smooth" });
                },
            };
        }
    </script>

</body>

</html>
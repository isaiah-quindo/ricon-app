@extends('layouts.admin')
@section('title', 'Edit Registration')

@php
    $inputClass = fn ($field) => 'w-full rounded-lg border ' . ($errors->has($field) ? 'border-red-400' : 'border-gray-200')
        . ' text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent';
@endphp

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.registrations.show', $registration) }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Registration
    </a>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.registrations.update', $registration) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Participant --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-base font-semibold text-gray-800">Edit: {{ $registration->first_name }} {{ $registration->last_name }}</h2>
                    <p class="text-xs text-gray-400 mt-1">Category and proof of payment can't be changed here.</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-400 mb-0.5">Category</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->raceCategory?->name ?? '—' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- First Name --}}
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" maxlength="255" required
                           value="{{ old('first_name', $registration->first_name) }}"
                           class="{{ $inputClass('first_name') }}" />
                    @error('first_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" maxlength="255" required
                           value="{{ old('last_name', $registration->last_name) }}"
                           class="{{ $inputClass('last_name') }}" />
                    @error('last_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sex --}}
                <div>
                    <label for="sex" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Sex <span class="text-red-500">*</span>
                    </label>
                    <select id="sex" name="sex" required class="{{ $inputClass('sex') }}">
                        @foreach (['male' => 'Male', 'female' => 'Female'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('sex', $registration->sex) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sex')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Birthdate --}}
                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Birthdate <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="birthdate" name="birthdate" required max="{{ now()->subDay()->format('Y-m-d') }}"
                           value="{{ old('birthdate', $registration->birthdate?->format('Y-m-d')) }}"
                           class="{{ $inputClass('birthdate') }}" />
                    @error('birthdate')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" maxlength="255" required
                           value="{{ old('email', $registration->email) }}"
                           class="{{ $inputClass('email') }}" />
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mobile --}}
                <div>
                    <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="mobile_number" name="mobile_number" maxlength="20" required
                           value="{{ old('mobile_number', $registration->mobile_number) }}"
                           class="{{ $inputClass('mobile_number') }}" />
                    @error('mobile_number')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nationality --}}
                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nationality <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nationality" name="nationality" maxlength="100" required
                           value="{{ old('nationality', $registration->nationality) }}"
                           class="{{ $inputClass('nationality') }}" />
                    @error('nationality')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Affiliation --}}
                <div>
                    <label for="affiliation" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Club / Affiliation
                    </label>
                    <input type="text" id="affiliation" name="affiliation" maxlength="255"
                           value="{{ old('affiliation', $registration->affiliation) }}"
                           class="{{ $inputClass('affiliation') }}" />
                    @error('affiliation')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Shirt Size --}}
                <div>
                    <label for="shirt_size" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Shirt Size <span class="text-red-500">*</span>
                    </label>
                    <select id="shirt_size" name="shirt_size" required class="{{ $inputClass('shirt_size') }}">
                        @foreach ($shirtSizes as $size)
                            <option value="{{ $size }}" @selected(old('shirt_size', $registration->shirt_size) === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    @error('shirt_size')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="address" name="address" maxlength="255" required
                           value="{{ old('address', $registration->address) }}"
                           class="{{ $inputClass('address') }}" />
                    @error('address')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-5">Emergency Contact</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" maxlength="255" required
                           value="{{ old('emergency_contact_name', $registration->emergency_contact_name) }}"
                           class="{{ $inputClass('emergency_contact_name') }}" />
                    @error('emergency_contact_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="emergency_contact_number" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="emergency_contact_number" name="emergency_contact_number" maxlength="20" required
                           value="{{ old('emergency_contact_number', $registration->emergency_contact_number) }}"
                           class="{{ $inputClass('emergency_contact_number') }}" />
                    @error('emergency_contact_number')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Save Changes
            </button>
            <a href="{{ route('admin.registrations.show', $registration) }}"
               class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection

@extends('layouts.admin')
@section('title', 'New Discount Code')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.discount-codes.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Discount Codes
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Code Details</h2>

        <form method="POST" action="{{ route('admin.discount-codes.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Code --}}
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="code" name="code"
                           value="{{ old('code') }}" required
                           placeholder="e.g. SAVE10"
                           style="text-transform:uppercase"
                           class="w-full rounded-lg border {{ $errors->has('code') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono" />
                    <p class="text-xs text-gray-400 mt-1">Letters, numbers, dashes and underscores only.</p>
                    @error('code')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Discount % --}}
                <div>
                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Discount Percentage <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="discount_percentage" name="discount_percentage"
                               value="{{ old('discount_percentage') }}" required min="0.01" max="100" step="0.01"
                               placeholder="e.g. 10"
                               class="w-full rounded-lg border {{ $errors->has('discount_percentage') ? 'border-red-400' : 'border-gray-200' }} text-sm pr-8 pl-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                    </div>
                    @error('discount_percentage')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Max Uses --}}
                <div>
                    <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Max Uses
                    </label>
                    <input type="number" id="max_uses" name="max_uses"
                           value="{{ old('max_uses') }}" min="1"
                           placeholder="Leave blank for unlimited"
                           class="w-full rounded-lg border {{ $errors->has('max_uses') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('max_uses')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Expires At --}}
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Expires At
                    </label>
                    <input type="datetime-local" id="expires_at" name="expires_at"
                           value="{{ old('expires_at') }}"
                           class="w-full rounded-lg border {{ $errors->has('expires_at') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    <p class="text-xs text-gray-400 mt-1">Leave blank for no expiry.</p>
                    @error('expires_at')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Race Categories --}}
                @include('admin.discount_codes._category_picker', ['selected' => []])

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Internal Description</label>
                    <input type="text" id="description" name="description"
                           value="{{ old('description') }}"
                           placeholder="Optional note for admins (not shown to participants)"
                           class="w-full rounded-lg border {{ $errors->has('description') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- One per email --}}
                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="one_per_email" value="0">
                        <input type="checkbox" name="one_per_email" value="1"
                               {{ old('one_per_email') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">One use per email</span>
                            <p class="text-xs text-gray-400">Each email address can redeem this code at most once.</p>
                        </div>
                    </label>
                </div>

                {{-- Active toggle --}}
                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-400">Available for use on the registration form.</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Create Code
                </button>
                <a href="{{ route('admin.discount-codes.index') }}"
                   class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

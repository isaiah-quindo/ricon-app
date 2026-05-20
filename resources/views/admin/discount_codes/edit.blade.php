@extends('layouts.admin')
@section('title', 'Edit Discount Code')

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
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-semibold text-gray-800">Edit: <span class="font-mono">{{ $discountCode->code }}</span></h2>
            <span class="text-xs text-gray-400">Used {{ $discountCode->used_count }} time(s)</span>
        </div>

        <form method="POST" action="{{ route('admin.discount-codes.update', $discountCode) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="code" name="code"
                           value="{{ old('code', $discountCode->code) }}" required
                           style="text-transform:uppercase"
                           class="w-full rounded-lg border {{ $errors->has('code') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono" />
                    @error('code')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="race_category_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Race Category <span class="text-red-500">*</span>
                    </label>
                    <select id="race_category_id" name="race_category_id" required
                            class="w-full rounded-lg border {{ $errors->has('race_category_id') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('race_category_id', $discountCode->race_category_id) === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} (₱{{ number_format($cat->price, 2) }})
                                @if(! $cat->is_active) — inactive @endif
                            </option>
                        @endforeach
                    </select>
                    @error('race_category_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Discount Percentage <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="discount_percentage" name="discount_percentage"
                               value="{{ old('discount_percentage', $discountCode->discount_percentage) }}" required min="0.01" max="100" step="0.01"
                               class="w-full rounded-lg border {{ $errors->has('discount_percentage') ? 'border-red-400' : 'border-gray-200' }} text-sm pr-8 pl-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                    </div>
                    @error('discount_percentage')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Max Uses
                    </label>
                    <input type="number" id="max_uses" name="max_uses"
                           value="{{ old('max_uses', $discountCode->max_uses) }}" min="1"
                           placeholder="Leave blank for unlimited"
                           class="w-full rounded-lg border {{ $errors->has('max_uses') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('max_uses')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Expires At
                    </label>
                    <input type="datetime-local" id="expires_at" name="expires_at"
                           value="{{ old('expires_at', optional($discountCode->expires_at)->format('Y-m-d\\TH:i')) }}"
                           class="w-full rounded-lg border {{ $errors->has('expires_at') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('expires_at')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Internal Description</label>
                    <input type="text" id="description" name="description"
                           value="{{ old('description', $discountCode->description) }}"
                           class="w-full rounded-lg border {{ $errors->has('description') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="one_per_email" value="0">
                        <input type="checkbox" name="one_per_email" value="1"
                               {{ old('one_per_email', $discountCode->one_per_email) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">One use per email</span>
                            <p class="text-xs text-gray-400">Each email address can redeem this code at most once.</p>
                        </div>
                    </label>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $discountCode->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-400">Uncheck to retire this code (preferred over deletion).</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Save Changes
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

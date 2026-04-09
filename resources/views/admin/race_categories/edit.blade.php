@extends('layouts.admin')
@section('title', 'Edit Race Category')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.race-categories.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Categories
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-semibold text-gray-800">Edit: {{ $raceCategory->name }}</h2>
            <span class="text-xs text-gray-400 font-mono">{{ $raceCategory->slug }}</span>
        </div>

        <form method="POST" action="{{ route('admin.race-categories.update', $raceCategory) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Name --}}
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $raceCategory->name) }}" required
                           class="w-full rounded-lg border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Distance --}}
                <div>
                    <label for="distance_km" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Distance (km) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="distance_km" name="distance_km"
                           value="{{ old('distance_km', $raceCategory->distance_km) }}" required
                           class="w-full rounded-lg border {{ $errors->has('distance_km') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('distance_km')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Elevation --}}
                <div>
                    <label for="elevation_m" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Elevation Gain (m)
                    </label>
                    <input type="text" id="elevation_m" name="elevation_m"
                           value="{{ old('elevation_m', $raceCategory->elevation_m) }}"
                           class="w-full rounded-lg border {{ $errors->has('elevation_m') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('elevation_m')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Entry Fee (₱) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₱</span>
                        <input type="number" id="price" name="price"
                               value="{{ old('price', $raceCategory->price) }}" required min="0" step="0.01"
                               class="w-full rounded-lg border {{ $errors->has('price') ? 'border-red-400' : 'border-gray-200' }} text-sm pl-8 pr-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    </div>
                    @error('price')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Max Slots --}}
                <div>
                    <label for="max_slots" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Max Slots <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="max_slots" name="max_slots"
                           value="{{ old('max_slots', $raceCategory->max_slots) }}" required min="1"
                           class="w-full rounded-lg border {{ $errors->has('max_slots') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    @error('max_slots')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bib Start Number --}}
                <div>
                    <label for="bib_start_number" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Bib Start Number <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="bib_start_number" name="bib_start_number"
                           value="{{ old('bib_start_number', $raceCategory->bib_start_number) }}" required min="1"
                           class="w-full rounded-lg border {{ $errors->has('bib_start_number') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    <p class="text-xs text-gray-400 mt-1">Changing this won't affect existing bib numbers.</p>
                    @error('bib_start_number')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full rounded-lg border {{ $errors->has('description') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none">{{ old('description', $raceCategory->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active toggle --}}
                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $raceCategory->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-400">Visible and open for registration.</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.race-categories.index') }}"
                   class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

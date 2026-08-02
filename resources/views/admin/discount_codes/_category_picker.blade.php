{{--
    Multi-select for the categories a code covers.

    $categories — options to offer
    $selected   — array of currently selected ids
--}}
@php($selected = collect(old('race_category_ids', $selected ?? []))->map(fn ($v) => (string) $v)->all())

{{-- Full width: the checkbox grid needs the room once there are several categories. --}}
<div class="sm:col-span-2"
     x-data="{
        ids: @js($selected),
        get all() { return this.ids.length === {{ $categories->count() }} },
        toggleAll() { this.ids = this.all ? [] : @js($categories->pluck('id')->map(fn ($id) => (string) $id)->all()) },
     }">
    <div class="flex items-center justify-between mb-1.5">
        <label class="block text-sm font-medium text-gray-700">
            Race Categories <span class="text-red-500">*</span>
        </label>
        <button type="button" @click="toggleAll()"
            class="text-xs text-indigo-600 hover:text-indigo-700 font-medium"
            x-text="all ? 'Clear all' : 'Select all'">Select all</button>
    </div>
    <p class="text-xs text-gray-500 mb-2">
        The code applies to entries in any of the categories you tick.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 rounded-lg border {{ $errors->has('race_category_ids') ? 'border-red-400' : 'border-gray-200' }} p-3">
        @foreach($categories as $cat)
        <label class="flex items-center gap-2.5 cursor-pointer rounded-lg px-2 py-1.5 hover:bg-gray-50">
            <input type="checkbox" name="race_category_ids[]" value="{{ $cat->id }}"
                x-model="ids"
                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm text-gray-800">
                {{ $cat->name }}
                <span class="text-gray-400">₱{{ number_format($cat->price, 2) }}</span>
                @unless($cat->is_active)
                <span class="text-xs text-amber-600">(inactive)</span>
                @endunless
            </span>
        </label>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-1.5">
        <span x-text="ids.length"></span> of {{ $categories->count() }} selected
    </p>

    @error('race_category_ids')
    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

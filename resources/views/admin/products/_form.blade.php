@php
$field = 'w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent';
$border = fn(string $name) => $errors->has($name) ? 'border-red-400' : 'border-gray-200';
$label = 'block text-sm font-medium text-gray-700 mb-1.5';

$existing = $product->colors?->keyBy('id') ?? collect();
$colorRows = collect(old('colors', $product->colors?->map(fn($c) => [
    'id' => $c->id, 'label' => $c->label, 'hex' => $c->hex,
])->all() ?? []))->values()->map(fn($c, $i) => [
    'key'   => 'c' . $i,
    'id'    => $c['id'] ?? null,
    'label' => $c['label'] ?? '',
    'hex'   => $c['hex'] ?? '#888888',
    'front' => isset($c['id']) ? $existing[$c['id']]?->front_url : null,
    'back'  => isset($c['id']) ? $existing[$c['id']]?->back_url : null,
]);
if ($colorRows->isEmpty()) {
    $colorRows = collect([['key' => 'c0', 'id' => null, 'label' => '', 'hex' => '#888888', 'front' => null, 'back' => null]]);
}

$specsText = old('specs_text', collect($product->specs ?? [])->map(fn($v, $k) => "{$k}: {$v}")->implode("\n"));
$sizesText = old('sizes_text', implode(', ', $product->sizes ?? []));
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label for="name" class="{{ $label }}">Name <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="{{ $field }} {{ $border('name') }}">
        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="slug" class="{{ $label }}">URL slug</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="Generated from the name" class="{{ $field }} {{ $border('slug') }}">
        <p class="text-xs text-gray-400 mt-1">Used as the section link, e.g. /shop#trail-cap</p>
        @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="eyebrow" class="{{ $label }}">Eyebrow</label>
        <input type="text" id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $product->eyebrow) }}" placeholder="e.g. New Drop" class="{{ $field }} {{ $border('eyebrow') }}">
        @error('eyebrow') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="price" class="{{ $label }}">Price (₱) <span class="text-red-500">*</span></label>
        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01" class="{{ $field }} {{ $border('price') }}">
        @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="compare_at_price" class="{{ $label }}">Struck-through price (₱)</label>
        <input type="number" id="compare_at_price" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" min="0" step="0.01" class="{{ $field }} {{ $border('compare_at_price') }}">
        @error('compare_at_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="usd_price" class="{{ $label }}">Approx. USD</label>
        <input type="number" id="usd_price" name="usd_price" value="{{ old('usd_price', $product->usd_price) }}" min="0" class="{{ $field }} {{ $border('usd_price') }}">
        @error('usd_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="sort_order" class="{{ $label }}">Display order</label>
        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0" class="{{ $field }} {{ $border('sort_order') }}">
        <p class="text-xs text-gray-400 mt-1">Lower shows first on /shop.</p>
    </div>

    <div class="sm:col-span-2">
        <label for="intro" class="{{ $label }}">Section intro</label>
        <textarea id="intro" name="intro" rows="2" class="{{ $field }} {{ $border('intro') }} resize-none">{{ old('intro', $product->intro) }}</textarea>
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="{{ $label }}">Description</label>
        <textarea id="description" name="description" rows="3" class="{{ $field }} {{ $border('description') }} resize-none">{{ old('description', $product->description) }}</textarea>
    </div>

    <div>
        <label for="specs_text" class="{{ $label }}">Specs</label>
        <textarea id="specs_text" name="specs_text" rows="4" placeholder="Fit: Classic unisex fit&#10;Fabric: 100% cotton" class="{{ $field }} {{ $border('specs_text') }} resize-none font-mono text-xs">{{ $specsText }}</textarea>
        <p class="text-xs text-gray-400 mt-1">One per line, as <span class="font-mono">Label: value</span></p>
    </div>

    <div>
        <label for="sizes_text" class="{{ $label }}">Sizes</label>
        <input type="text" id="sizes_text" name="sizes_text" value="{{ $sizesText }}" placeholder="S, M, L, XL" class="{{ $field }} {{ $border('sizes_text') }}">
        <p class="text-xs text-gray-400 mt-1">Comma separated. Leave empty for one-size items. Removing a size deletes its stock.</p>
    </div>

    <div class="sm:col-span-2">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            Show on the public shop
        </label>
    </div>
</div>

{{-- Colors --}}
<div class="mt-8" x-data="{ rows: @js($colorRows), next: {{ $colorRows->count() }} }">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h3 class="text-sm font-semibold text-gray-800">Colors &amp; Photos</h3>
            <p class="text-xs text-gray-400">Each color gets its own stock per size. Removing a color deletes its stock. Photos: square PNG works best.</p>
        </div>
        <button type="button" @click="rows.push({ key: 'c' + next++, id: null, label: '', hex: '#888888', front: null, back: null })"
            class="px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">+ Add Color</button>
    </div>
    @error('colors') <p class="text-xs text-red-500 mb-2">{{ $message }}</p> @enderror

    <div class="space-y-3">
        <template x-for="(row, i) in rows" :key="row.key">
            <div class="border border-gray-200 rounded-lg p-4">
                <input type="hidden" :name="'colors[' + row.key + '][id]'" :value="row.id" :disabled="!row.id">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-40">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Color name</label>
                        <input type="text" :name="'colors[' + row.key + '][label]'" x-model="row.label" required placeholder="e.g. Army Green" class="{{ $field }} border-gray-200">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Swatch</label>
                        <div class="flex items-center gap-2">
                            <input type="color" x-model="row.hex" class="w-10 h-10 rounded border border-gray-200 p-0.5 bg-white">
                            <input type="text" :name="'colors[' + row.key + '][hex]'" x-model="row.hex" class="w-24 {{ $field }} border-gray-200 font-mono text-xs">
                        </div>
                    </div>
                    <button type="button" @click="if (!row.id || confirm('Remove ' + (row.label || 'this color') + '? Its stock will be deleted when you save.')) rows.splice(i, 1)"
                        x-show="rows.length > 1" class="px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Remove</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                    <div class="flex items-center gap-3">
                        <img :src="row.front" x-show="row.front" class="w-14 h-14 rounded bg-gray-100 object-contain border border-gray-200">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Front photo <span x-show="row.front" class="text-gray-400">(upload to replace)</span></label>
                            <input type="file" :name="'colors[' + row.key + '][front_image]'" accept="image/png,image/jpeg,image/webp" class="text-xs text-gray-600">
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <img :src="row.back" x-show="row.back" class="w-14 h-14 rounded bg-gray-100 object-contain border border-gray-200">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Back photo (optional)</label>
                            <input type="file" :name="'colors[' + row.key + '][back_image]'" accept="image/png,image/jpeg,image/webp" class="text-xs text-gray-600">
                            <label x-show="row.back" class="flex items-center gap-1.5 text-xs text-gray-500 mt-1">
                                <input type="checkbox" :name="'colors[' + row.key + '][remove_back]'" value="1" class="rounded border-gray-300"> Remove back photo
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
    @if($errors->has('colors.*'))
    <div class="mt-2 text-xs text-red-500">
        @foreach($errors->get('colors.*') as $messages)
        @foreach($messages as $message) <p>{{ $message }}</p> @endforeach
        @endforeach
    </div>
    @endif
</div>

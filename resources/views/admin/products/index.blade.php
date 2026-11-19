@extends('layouts.admin')
@section('title', 'Inventory')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div>
        <h2 class="text-base font-semibold text-gray-800">Merch Inventory</h2>
        <p class="text-sm text-gray-500">In stock is what can still be bought. Sold counts items on orders that haven't been cancelled or rejected.</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
        + New Product
    </a>
</div>

<div class="space-y-4">
    @forelse($products as $product)
    @php
    $sizes = $product->sizes ?: [null];
    $byColor = $product->variants->groupBy('product_color_id');
    $inStock = $product->variants->sum('stock');
    $committed = $product->variants->sum(fn($v) => $sold[$v->id] ?? 0);
    @endphp
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-4">
                @if($img = $product->colors->first()?->front_url)
                <img src="{{ $img }}" alt="" class="w-12 h-12 rounded-lg bg-gray-100 object-contain border border-gray-200">
                @endif
                <div>
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-900">{{ $product->name }}</p>
                        @unless($product->is_active)
                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-500 border border-gray-200">Hidden</span>
                        @endunless
                    </div>
                    <p class="text-xs text-gray-500">&#8369;{{ number_format($product->price, 2) }} &middot; /shop#{{ $product->slug }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-xs text-gray-400">In stock</p>
                    <p class="text-lg font-bold {{ $inStock === 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $inStock }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Sold</p>
                    <p class="text-lg font-bold text-gray-900">{{ $committed }}</p>
                </div>
                <a href="{{ route('admin.products.edit', $product) }}"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                    Edit &amp; Restock
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Color</th>
                        @foreach($sizes as $size)
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $size ?? 'One size' }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($product->colors as $color)
                    @php $variants = ($byColor[$color->id] ?? collect())->keyBy(fn($v) => $v->size ?? '_'); @endphp
                    <tr>
                        <td class="px-5 py-2.5 whitespace-nowrap">
                            <span class="inline-block w-3 h-3 rounded-full border border-gray-300 align-middle mr-2" style="background: {{ $color->hex }}"></span>
                            <span class="text-gray-800">{{ $color->label }}</span>
                        </td>
                        @foreach($sizes as $size)
                        @php $v = $variants[$size ?? '_'] ?? null; @endphp
                        <td class="px-4 py-2.5 text-center tabular-nums">
                            @if($v)
                            <span class="font-semibold {{ $v->stock === 0 ? 'text-red-600' : ($v->stock <= 5 ? 'text-amber-600' : 'text-gray-900') }}">{{ $v->stock }}</span>
                            @if($sold[$v->id] ?? 0)
                            <span class="text-xs text-gray-400">/ {{ $sold[$v->id] }} sold</span>
                            @endif
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 px-5 py-12 text-center text-sm text-gray-400">
        No products yet.
    </div>
    @endforelse
</div>

@endsection

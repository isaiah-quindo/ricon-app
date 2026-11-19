@extends('layouts.admin')
@section('title', 'Edit ' . $product->name)

@section('content')

@php
$sizes = $product->sizes ?: [null];
$byColor = $product->variants->groupBy('product_color_id');
@endphp

<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Inventory
    </a>
</div>

<div class="max-w-3xl space-y-6">

    {{-- Stock --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6" x-data>
        <h2 class="text-base font-semibold text-gray-800 mb-1">Stock: {{ $product->name }}</h2>
        <p class="text-sm text-gray-500 mb-5">
            Enter how many are on hand and available to sell. Orders take stock out automatically when placed and
            give it back when cancelled or rejected. Use <span class="font-semibold">+</span> and <span class="font-semibold">&minus;</span> to adjust by one, or type a number. Changes apply when you save.
        </p>

        <form method="POST" action="{{ route('admin.products.updateStock', $product) }}">
            @csrf
            @method('PATCH')
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-2 pr-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Color</th>
                            @foreach($sizes as $size)
                            <th class="pb-2 px-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $size ?? 'Stock' }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($product->colors as $color)
                        @php $variants = ($byColor[$color->id] ?? collect())->keyBy(fn($v) => $v->size ?? '_'); @endphp
                        <tr>
                            <td class="py-3 pr-4 whitespace-nowrap">
                                <span class="inline-block w-3 h-3 rounded-full border border-gray-300 align-middle mr-2" style="background: {{ $color->hex }}"></span>
                                {{ $color->label }}
                            </td>
                            @foreach($sizes as $size)
                            @php $v = $variants[$size ?? '_'] ?? null; @endphp
                            <td class="py-3 px-2 text-center">
                                @if($v)
                                <div x-data="{ n: {{ old("stock.{$v->id}", $v->stock) }} }" class="inline-flex flex-col items-center">
                                    <input type="hidden" name="original[{{ $v->id }}]" value="{{ old("original.{$v->id}", $v->stock) }}">
                                    {{-- Joined stepper: [ - | n | + ] --}}
                                    <div class="inline-flex items-stretch h-9 rounded-lg border border-gray-200 bg-white overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent">
                                        <button type="button" @click="n = Math.max(0, (n || 0) - 1)" :disabled="(n || 0) <= 0"
                                            class="w-8 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-gray-50 disabled:opacity-30 disabled:hover:bg-transparent disabled:hover:text-gray-500 disabled:cursor-not-allowed"
                                            aria-label="Remove one {{ $color->label }}{{ $size ? ' ' . $size : '' }}">&minus;</button>
                                        <input type="number" name="stock[{{ $v->id }}]" x-model.number="n" min="0"
                                            aria-label="{{ $color->label }}{{ $size ? ' ' . $size : '' }} stock"
                                            class="w-12 border-0 border-x border-gray-200 p-0 text-sm text-center focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                                        <button type="button" @click="n = (n || 0) + 1"
                                            class="w-8 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-gray-50"
                                            aria-label="Add one {{ $color->label }}{{ $size ? ' ' . $size : '' }}">+</button>
                                    </div>
                                    {{-- Always reserve this line so every stepper in a row lines up --}}
                                    <span class="h-4 mt-1 text-[11px] leading-4 text-gray-400">{{ ($sold[$v->id] ?? 0) ? $sold[$v->id] . ' sold' : '' }}</span>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($errors->has('stock.*'))
            <p class="text-xs text-red-500 mt-2">Stock must be a whole number of 0 or more.</p>
            @endif
            <div class="flex justify-end mt-5">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">Save Stock</button>
            </div>
        </form>
    </div>

    {{-- Details --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-semibold text-gray-800">Product Details</h2>
            <a href="{{ route('shop.index') }}#{{ $product->slug }}" target="_blank" class="text-xs text-indigo-600 hover:underline">View on shop &nearr;</a>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.products._form')
            <div class="flex justify-end mt-8 pt-5 border-t border-gray-100">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">Save Details</button>
            </div>
        </form>
    </div>
</div>

@endsection

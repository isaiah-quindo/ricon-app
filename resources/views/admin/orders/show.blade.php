@extends('layouts.admin')
@section('title', 'Order Detail')

@section('content')

@php
$image = collect($product['images'] ?? [])->first();
@endphp

<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column: Order details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Header card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-xl font-bold text-gray-900 font-mono">{{ $order->reference }}</h2>
                        @include('admin.orders._status_badge', ['status' => $order->status])
                    </div>
                    <p class="text-sm text-gray-500">Submitted {{ $order->created_at->format('M j, Y g:i A') }}</p>
                </div>
            </div>
        </div>

        {{-- Buyer --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Buyer</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Name</p>
                    <p class="text-sm font-medium text-gray-800">{{ $order->full_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Email</p>
                    <p class="text-sm font-medium text-gray-800 break-all">
                        <a href="mailto:{{ $order->email }}" class="hover:text-indigo-600">{{ $order->email }}</a>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Mobile</p>
                    <p class="text-sm font-medium text-gray-800">{{ $order->mobile_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Updates Consent</p>
                    <p class="text-sm font-medium text-gray-800">{{ $order->notify_consent ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>

        {{-- Item --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Item</h3>
            <div class="flex gap-4 items-start">
                @if($image)
                <img src="{{ $image }}" alt="{{ $order->product_name }}"
                    class="w-20 h-20 object-cover rounded-lg border border-gray-200 bg-gray-50 flex-shrink-0" />
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ $order->product_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $order->size ? "Size {$order->size}" : 'One size' }}</p>
                </div>
            </div>

            <dl class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Unit Price</dt>
                    <dd class="text-sm text-gray-800">&#8369;{{ number_format($order->unit_price, 2) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Quantity</dt>
                    <dd class="text-sm text-gray-800">{{ $order->quantity }}</dd>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-100">
                    <dt class="text-sm font-semibold text-gray-800">Total</dt>
                    <dd class="text-sm font-bold text-gray-900">&#8369;{{ number_format($order->total, 2) }}</dd>
                </div>
            </dl>
        </div>

    </div>

    {{-- Right column: Status actions --}}
    <div class="space-y-4">

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Order Status</h3>
            <div class="mb-4">
                @include('admin.orders._status_badge', ['status' => $order->status])
                @if($order->status_changed_at)
                <p class="text-xs text-gray-400 mt-2">
                    Updated {{ $order->status_changed_at->format('M j, Y g:i A') }}
                    @if($order->statusChangedBy) by {{ $order->statusChangedBy->name }} @endif
                </p>
                @endif
            </div>

            @if($order->status === 'pending')
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="mb-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="fulfilled">
                <button type="submit"
                    onclick="return confirm('Mark {{ $order->reference }} as fulfilled?')"
                    class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Mark as Fulfilled
                </button>
            </form>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit"
                    onclick="return confirm('Cancel order {{ $order->reference }}?')"
                    class="w-full px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                    Cancel Order
                </button>
            </form>
            @else
            <p class="text-xs text-gray-500 mb-3">
                Marked by mistake? Move the order back to pending to change it.
            </p>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="pending">
                <button type="submit"
                    onclick="return confirm('Move {{ $order->reference }} back to pending?')"
                    class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Move Back to Pending
                </button>
            </form>
            @endif
        </div>

    </div>

</div>

@endsection

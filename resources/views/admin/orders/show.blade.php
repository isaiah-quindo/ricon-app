@extends('layouts.admin')
@section('title', 'Order ' . $order->reference)

@section('content')

@php
$proofUrl = $order->payment_proof_path ? Storage::disk('s3')->url($order->payment_proof_path) : null;
$field = 'w-full rounded-lg border border-gray-200 text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent';
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

@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
    @foreach($errors->all() as $message)
    <p>{{ $message }}</p>
    @endforeach
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ===================== Left: order ===================== --}}
    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-xl font-bold text-gray-900 font-mono">{{ $order->reference }}</h2>
                        @include('admin.orders._status_badge', ['status' => $order->status])
                    </div>
                    <p class="text-sm text-gray-500">Submitted {{ $order->created_at->format('M j, Y g:i A') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900">&#8369;{{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Buyer + shipping --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Buyer &amp; Shipping</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                    <p class="text-xs text-gray-400 mb-0.5">Shipping Area</p>
                    <p class="text-sm font-medium text-gray-800">{{ $order->shipping_area_label ?? 'Not set (reservation)' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">{{ $order->isPickup() ? 'Pickup' : 'Delivery Address' }}</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $order->isPickup() ? 'Pickup in Baguio City' : ($order->shipping_address ?? 'n/a') }}
                    </p>
                </div>
                @if($order->tracking_number)
                <div class="sm:col-span-3">
                    <p class="text-xs text-gray-400 mb-0.5">LBC Tracking Number</p>
                    <p class="text-sm font-medium text-gray-800">
                        <span class="font-mono">{{ $order->tracking_number }}</span>
                        <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener" class="ml-2 text-indigo-600 hover:underline text-xs">Track on LBC &nearr;</a>
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Items</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 border-b border-gray-100">
                        <th class="text-left font-medium pb-2">Item</th>
                        <th class="text-right font-medium pb-2">Price</th>
                        <th class="text-right font-medium pb-2">Qty</th>
                        <th class="text-right font-medium pb-2">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-3">
                            <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->options_label }}</p>
                        </td>
                        <td class="py-3 text-right text-gray-700">&#8369;{{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                        <td class="py-3 text-right font-medium text-gray-900">&#8369;{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <dl class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Subtotal</dt>
                    <dd class="text-sm text-gray-800">&#8369;{{ number_format($order->subtotal, 2) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Shipping</dt>
                    <dd class="text-sm text-gray-800">&#8369;{{ number_format($order->shipping_fee, 2) }}</dd>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-100">
                    <dt class="text-sm font-semibold text-gray-800">Total</dt>
                    <dd class="text-sm font-bold text-gray-900">&#8369;{{ number_format($order->total, 2) }}</dd>
                </div>
            </dl>
        </div>

        {{-- Proof of payment --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Proof of Payment</h3>
            @if($proofUrl)
            <div class="flex flex-wrap gap-5 items-start">
                <a href="{{ $proofUrl }}" target="_blank"
                    class="block rounded-lg overflow-hidden border border-gray-200 hover:border-indigo-300 transition-colors flex-shrink-0">
                    <img src="{{ $proofUrl }}" alt="Payment proof" class="w-48 h-48 object-cover" />
                </a>
                <div class="text-sm space-y-3">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Amount Expected</p>
                        <p class="font-bold text-gray-900">&#8369;{{ number_format($order->total, 2) }}</p>
                    </div>
                    @if($order->payment_verified_at)
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Verified</p>
                        <p class="text-gray-800">{{ $order->payment_verified_at->format('M j, Y g:i A') }}
                            @if($order->paymentVerifiedBy) by {{ $order->paymentVerifiedBy->name }} @endif</p>
                    </div>
                    @endif
                    <a href="{{ $proofUrl }}" target="_blank" class="inline-block text-xs text-indigo-600 hover:underline">Open full size &nearr;</a>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-500">No receipt uploaded. This is a pre-order reservation placed before checkout took payment.</p>
            @endif
        </div>

        @if($order->rejection_reason)
        <div class="bg-red-50 rounded-xl border border-red-200 p-6">
            <h3 class="text-sm font-semibold text-red-800 mb-1">Rejection Reason (sent to buyer)</h3>
            <p class="text-sm text-red-700">{!! nl2br(e($order->rejection_reason)) !!}</p>
        </div>
        @endif

        {{-- Notes --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Internal Notes</h3>
            <p class="text-xs text-gray-400 mb-3">Only visible to admins.</p>
            <form method="POST" action="{{ route('admin.orders.updateNotes', $order) }}">
                @csrf
                @method('PATCH')
                <textarea name="admin_notes" rows="3" class="{{ $field }} resize-none">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Save Notes</button>
            </form>
        </div>
    </div>

    {{-- ===================== Right: actions ===================== --}}
    <div class="space-y-4">

        {{-- Step 1: review payment --}}
        @if(in_array($order->status, ['pending', 'payment_submitted']))
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">1. Review Payment</h3>
            <p class="text-xs text-gray-500 mb-4">Check the receipt shows &#8369;{{ number_format($order->total, 2) }} sent to the RiCON account.</p>

            <form method="POST" action="{{ route('admin.orders.confirm', $order) }}" class="mb-4">
                @csrf
                <label class="flex items-center gap-2 text-sm text-gray-700 mb-3">
                    <input type="checkbox" name="send_email" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    Email the buyer a confirmation
                </label>
                <button type="submit" onclick="return confirm('Confirm payment for {{ $order->reference }}?')"
                    class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Confirm Payment
                </button>
            </form>

            <form method="POST" action="{{ route('admin.orders.reject', $order) }}" x-data="{ open: false }">
                @csrf
                <button type="button" x-show="!open" @click="open = true"
                    class="w-full px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                    Reject Payment&hellip;
                </button>
                <div x-show="open" style="display: none;">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Reason (included in the email)</label>
                    <textarea name="reason" rows="3" placeholder="e.g. The receipt shows ₱650 but the order total is ₱800."
                        class="{{ $field }} resize-none mb-2"></textarea>
                    <button type="submit" onclick="return confirm('Reject {{ $order->reference }}? Items go back to stock and the buyer is emailed.')"
                        class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Reject &amp; Email Buyer
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- Step 2: ship or hand over --}}
        @if(in_array($order->status, ['confirmed', 'shipped']))
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            @if($order->isPickup())
            <h3 class="text-sm font-semibold text-gray-800 mb-1">2. Pickup</h3>
            <p class="text-xs text-gray-500 mb-4">Pickup in Baguio City. Mark complete once the buyer has collected their order.</p>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Mark Picked Up
                </button>
            </form>
            @else
            <h3 class="text-sm font-semibold text-gray-800 mb-1">2. {{ $order->status === 'shipped' ? 'Shipped via LBC' : 'Ship via LBC' }}</h3>
            <p class="text-xs text-gray-500 mb-4">
                @if($order->status === 'shipped')
                Shipped {{ $order->shipped_at?->format('M j, Y g:i A') }}. Correct the tracking number below to re-send it.
                @else
                Enter the LBC tracking number. The buyer gets an email with a tracking link.
                @endif
            </p>
            <form method="POST" action="{{ route('admin.orders.ship', $order) }}">
                @csrf
                <label for="tracking_number" class="block text-xs font-medium text-gray-600 mb-1.5">LBC Tracking Number</label>
                <input type="text" id="tracking_number" name="tracking_number" required
                    value="{{ old('tracking_number', $order->tracking_number) }}"
                    class="{{ $field }} font-mono mb-3 {{ $errors->has('tracking_number') ? 'border-red-400' : '' }}">
                <button type="submit"
                    class="w-full px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    {{ $order->status === 'shipped' ? 'Update & Re-send Tracking' : 'Mark Shipped & Email Tracking' }}
                </button>
            </form>

            @if($order->status === 'shipped')
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="mt-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="w-full px-4 py-2 bg-green-50 text-green-700 border border-green-200 text-sm font-medium rounded-lg hover:bg-green-100 transition-colors">
                    Mark Delivered
                </button>
            </form>
            @endif
            @endif
        </div>
        @endif

        {{-- Emails --}}
        @if(in_array($order->status, ['confirmed', 'shipped', 'completed']))
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Confirmation Email</h3>
            <p class="text-xs text-gray-500 mb-3">
                @if($order->confirmation_emailed_at)
                Last sent {{ $order->confirmation_emailed_at->format('M j, Y g:i A') }}.
                @else
                Not sent yet.
                @endif
            </p>
            <form method="POST" action="{{ route('admin.orders.resendConfirmation', $order) }}">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                    {{ $order->confirmation_emailed_at ? 'Resend' : 'Send' }} Confirmation Email
                </button>
            </form>
        </div>
        @endif

        {{-- Manual status override --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Change Status</h3>
            <p class="text-xs text-gray-500 mb-3">Manual override. No email is sent. Cancelling or rejecting returns items to stock; reopening takes them back out.</p>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="flex gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="{{ $field }}">
                    @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" onclick="return confirm('Change the status of {{ $order->reference }}?')"
                    class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">Save</button>
            </form>
            @if($order->status_changed_at)
            <p class="text-xs text-gray-400 mt-3">
                Last changed {{ $order->status_changed_at->format('M j, Y g:i A') }}
                @if($order->statusChangedBy) by {{ $order->statusChangedBy->name }} @endif
            </p>
            @endif
        </div>
    </div>
</div>

@endsection

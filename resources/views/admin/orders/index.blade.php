@extends('layouts.admin')
@section('title', 'Orders')

@section('content')

@php
$currentSort = request('sort', 'created_at');
$currentDir = request('direction', 'desc');
$sortUrl = fn(string $col) => request()->fullUrlWithQuery([
'sort' => $col,
'direction' => ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc',
'page' => 1,
]);
$sortIcon = function (string $col) use ($currentSort, $currentDir): string {
if ($currentSort !== $col) {
return '<svg class="w-3 h-3 text-gray-300 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
</svg>';
}
return $currentDir === 'asc'
? '<svg class="w-3 h-3 text-indigo-600 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
</svg>'
: '<svg class="w-3 h-3 text-indigo-600 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
</svg>';
};
$select = 'w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent';
$hasFilters = collect(request()->only(['search', 'product', 'shipping_area', 'status']))->filter()->isNotEmpty();
$needsAction = ['payment_submitted' => 'to review', 'confirmed' => 'to ship'];
@endphp

{{-- Work queue --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    @foreach(['payment_submitted', 'confirmed', 'shipped', 'completed'] as $s)
    <a href="{{ route('admin.orders.index', ['status' => $s]) }}"
        class="bg-white rounded-xl border p-4 transition-colors {{ request('status') === $s ? 'border-indigo-400 ring-1 ring-indigo-400' : 'border-gray-200 hover:border-gray-300' }}">
        <p class="text-xs font-medium text-gray-500">{{ $statuses[$s] }}</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statusCounts[$s] ?? 0 }}</p>
        @isset($needsAction[$s])
        <p class="text-xs text-gray-400 mt-0.5">{{ $needsAction[$s] }}</p>
        @endisset
    </a>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.orders.index') }}"
    class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3">

    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, mobile, reference, tracking"
            class="{{ $select }}">
    </div>

    <div class="min-w-44">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Item</label>
        <select name="product" class="{{ $select }}">
            <option value="">All items</option>
            @foreach($products as $name)
            <option value="{{ $name }}" @selected(request('product') === $name)>{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Shipping</label>
        <select name="shipping_area" class="{{ $select }}">
            <option value="">All areas</option>
            @foreach($shippingAreas as $key => $area)
            <option value="{{ $key }}" @selected(request('shipping_area') === $key)>{{ $area['label'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="min-w-32">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
        <select name="status" class="{{ $select }}">
            <option value="">All statuses</option>
            @foreach($statuses as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2">
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if($hasFilters)
        <a href="{{ route('admin.orders.index') }}"
            class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Clear
        </a>
        @endif
    </div>

    <a href="{{ route('admin.orders.export', request()->query()) }}"
        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
        Export CSV
    </a>

</form>
<div class="flex items-center justify-end mb-4 px-4">
    <span class="text-sm text-gray-900">
        {{ $orders->total() }} result{{ $orders->total() !== 1 ? 's' : '' }} ({{ $total }} total)
    </span>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('full_name') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Name {!! $sortIcon('full_name') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Shipping</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('total') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Total {!! $sortIcon('total') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('status') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Status {!! $sortIcon('status') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Submitted {!! $sortIcon('created_at') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 text-sm font-mono font-semibold text-gray-700 whitespace-nowrap">{{ $order->reference }}</td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-semibold text-gray-900">{{ $order->full_name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->email }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @foreach($order->items as $item)
                        <p class="text-xs text-gray-700 whitespace-nowrap">
                            <span class="font-semibold">{{ $item->quantity }}&times;</span> {{ $item->product_name }}
                            <span class="text-gray-400">&middot; {{ $item->options_label }}</span>
                        </p>
                        @endforeach
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-600 whitespace-nowrap">
                        {{ $order->shipping_area_label ?? 'n/a' }}
                        @if($order->tracking_number)
                        <p class="font-mono text-gray-400 mt-0.5">{{ $order->tracking_number }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm font-semibold text-gray-900 text-right whitespace-nowrap">&#8369;{{ number_format($order->total) }}</td>
                    <td class="px-5 py-4">
                        @include('admin.orders._status_badge', ['status' => $order->status])
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $order->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                            View
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-sm">No orders yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection

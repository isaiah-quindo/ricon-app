@extends('layouts.admin')
@section('title', 'Shuttle RSVPs')

@use('App\Models\ShuttleRsvp')

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
$hasFilters = collect(request()->only(['search', 'shuttle_date', 'pickup_point', 'distance']))->filter()->isNotEmpty();
@endphp

{{-- Filters --}}
<form method="GET" action="{{ route('admin.shuttle-rsvps.index') }}"
    class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3">

    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, or mobile"
            class="{{ $select }}">
    </div>

    <div class="min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Date</label>
        <select name="shuttle_date" class="{{ $select }}">
            <option value="">All dates</option>
            @foreach(ShuttleRsvp::SHUTTLE_DATES as $value => $dateLabel)
            <option value="{{ $value }}" @selected(request('shuttle_date') === $value)>{{ $dateLabel }}</option>
            @endforeach
        </select>
    </div>

    <div class="min-w-32">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Pickup</label>
        <select name="pickup_point" class="{{ $select }}">
            <option value="">All pickups</option>
            @foreach(array_keys(ShuttleRsvp::PICKUP_POINTS) as $pickup)
            <option value="{{ $pickup }}" @selected(request('pickup_point') === $pickup)>{{ $pickup }}</option>
            @endforeach
        </select>
    </div>

    <div class="min-w-32">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Distance</label>
        <select name="distance" class="{{ $select }}">
            <option value="">All distances</option>
            @foreach(ShuttleRsvp::DISTANCES as $distance)
            <option value="{{ $distance }}" @selected(request('distance') === $distance)>{{ $distance }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2">
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if($hasFilters)
        <a href="{{ route('admin.shuttle-rsvps.index') }}"
            class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Clear
        </a>
        @endif
    </div>

    <a href="{{ route('admin.shuttle-rsvps.export', request()->query()) }}"
        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
        Export CSV
    </a>

</form>
<div class="flex items-center justify-end mb-4 px-4">
    <span class="text-sm text-gray-900">
        {{ $rsvps->total() }} result{{ $rsvps->total() !== 1 ? 's' : '' }} ({{ $total }} total)
    </span>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('full_name') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Name {!! $sortIcon('full_name') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mobile</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Distance</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('shuttle_date') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Date {!! $sortIcon('shuttle_date') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pickup</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('seats') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Seats {!! $sortIcon('seats') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Return</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Notes</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Submitted {!! $sortIcon('created_at') !!}
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($rsvps as $rsvp)
                <tr class="hover:bg-gray-50 transition-colors align-top">
                    <td class="px-5 py-4">
                        <p class="text-sm font-semibold text-gray-900">{{ $rsvp->full_name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $rsvp->email }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $rsvp->mobile_number }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2 py-1 bg-orange-50 border border-orange-200 text-orange-700 text-xs font-medium rounded-md">
                            {{ $rsvp->distance }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $rsvp->shuttle_date->format('D, M j') }}</td>
                    <td class="px-5 py-4 text-sm text-gray-700">{{ $rsvp->pickup_point }}</td>
                    <td class="px-5 py-4 text-sm font-semibold text-gray-900">{{ $rsvp->seats }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500">{{ $rsvp->return_trip ? 'Yes' : 'No' }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 max-w-xs">
                        @if($rsvp->notes)
                        <p class="line-clamp-2" title="{{ $rsvp->notes }}">{{ $rsvp->notes }}</p>
                        @else
                        <span class="text-gray-300">&mdash;</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $rsvp->created_at->format('M j, Y g:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm">No shuttle RSVPs yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rsvps->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $rsvps->links() }}
    </div>
    @endif
</div>

@endsection

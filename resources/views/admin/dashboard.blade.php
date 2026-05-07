@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Stats cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Total</span>
            <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
        <p class="text-xs text-gray-400 mt-1">All registrations</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Pending</span>
            <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending']) }}</p>
        <p class="text-xs text-gray-400 mt-1">Awaiting review</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Approved</span>
            <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['approved']) }}</p>
        <p class="text-xs text-gray-400 mt-1">Confirmed runners</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">Rejected</span>
            <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['rejected']) }}</p>
        <p class="text-xs text-gray-400 mt-1">Not approved</p>
    </div>


</div>

{{-- Revenue card --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-8">
    <div class="flex items-start justify-between mb-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-base font-semibold text-gray-800">Revenue</h2>
            </div>
            <p class="text-3xl font-bold text-gray-900">₱{{ number_format($stats['revenue'], 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Total from approved registrations</p>
        </div>
        <div class="inline-flex rounded-lg border border-gray-200 p-0.5 bg-gray-50">
            <a href="{{ route('admin.dashboard', ['range' => 7]) }}"
               class="px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $range === 7 ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                7 days
            </a>
            <a href="{{ route('admin.dashboard', ['range' => 30]) }}"
               class="px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $range === 30 ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                30 days
            </a>
        </div>
    </div>
    <div class="relative h-64">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

{{-- Category breakdown --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-800">Registrations by Category</h2>
        <a href="{{ route('admin.registrations.index') }}"
            class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Distance</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Max Slots</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Registered</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Approved</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fill Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($byCategory as $category)
                @php
                $fillRate = $category->max_slots > 0
                ? round(($category->approved_count / $category->max_slots) * 100)
                : 0;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-900 text-sm">{{ $category->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $category->distance_km }} km</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($category->max_slots) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ number_format($category->registrations_count) }}</td>
                    <td class="px-6 py-4 text-sm text-green-700 font-medium">{{ number_format($category->approved_count) }}</td>
                    <td class="px-6 py-4 text-sm text-emerald-700 font-medium">₱{{ number_format($category->approved_revenue ?? 0, 2) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-100 rounded-full h-1.5 min-w-16">
                                <div class="h-1.5 rounded-full {{ $fillRate >= 90 ? 'bg-red-500' : ($fillRate >= 70 ? 'bg-amber-500' : 'bg-green-500') }}"
                                    @style('width: ' . min($fillRate, 100) . '%')></div>
                            </div>
                            <span class="text-xs text-gray-500 w-8">{{ $fillRate }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($category->is_active)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2.5 py-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded-full px-2.5 py-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-400">
                        No race categories found.
                        <a href="{{ route('admin.race-categories.create') }}" class="text-indigo-600 hover:underline ml-1">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('revenueChart');
        if (!ctx || !window.Chart) return;
        new window.Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($revenueSeries->pluck('date')),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenueSeries->pluck('total')),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (c) => '₱' + Number(c.parsed.y).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { display: false },
                        ticks: { callback: (v) => '₱' + Number(v).toLocaleString('en-PH') },
                    },
                },
            },
        });
    });
</script>
@endpush

@endsection
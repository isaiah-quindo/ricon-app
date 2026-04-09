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
                    <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-400">
                        No race categories found.
                        <a href="{{ route('admin.race-categories.create') }}" class="text-indigo-600 hover:underline ml-1">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
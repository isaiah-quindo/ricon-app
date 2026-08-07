@extends('layouts.admin')
@section('title', 'Group Registrations')

@section('content')

<div class="flex items-center justify-between gap-4 mb-4">
    <a href="{{ route('admin.registrations.index') }}"
        class="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        All registrations
    </a>
    <h1 class="text-sm font-semibold text-gray-800">Group Registrations</h1>
</div>

{{-- Summary tiles --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Groups</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totals['groups']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Participants</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totals['participants']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Awaiting payment</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($totals['awaiting']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Verified value</p>
        <p class="text-2xl font-bold text-green-600 mt-1">₱{{ number_format($totals['verified_due'], 2) }}</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.registration-groups.index') }}"
    class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3">

    <div class="flex-1 min-w-60">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Search</label>
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Reference, organizer, team or email"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
    </div>

    <div class="flex-1 min-w-44">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Payment</label>
        <select name="payment_status"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All</option>
            @foreach(['pending' => 'Awaiting payment', 'verified' => 'Paid', 'rejected' => 'Rejected'] as $value => $label)
            <option value="{{ $value }}" {{ request('payment_status') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2">
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request('search') || request('payment_status'))
        <a href="{{ route('admin.registration-groups.index') }}"
            class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Clear
        </a>
        @endif
    </div>
</form>

<div class="text-sm text-gray-900 mb-4 px-4">
    {{ $groups->total() }} group{{ $groups->total() !== 1 ? 's' : '' }}
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            @php
                $currentSort = request('sort', 'created_at');
                $currentDir  = request('direction', 'desc');
                $sortUrl = fn (string $col) => request()->fullUrlWithQuery([
                    'sort' => $col,
                    'direction' => ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc',
                    'page' => 1,
                ]);
            @endphp
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Organizer</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('participant_count') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">Size</a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Approved</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('total_due') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">Total</a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('payment_status') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">Payment</a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">Submitted</a>
                    </th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($groups as $group)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <span class="font-mono text-sm font-semibold text-gray-900">{{ $group->reference_code }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $group->organizer_name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $group->organizer_email ?? $group->leader_email }}
                            @if($group->organizer_team) · {{ $group->organizer_team }} @endif
                        </p>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-700">{{ $group->participant_count }}</td>
                    <td class="px-5 py-4">
                        @php $approved = $group->approvedCount(); @endphp
                        <span class="text-sm {{ $approved === $group->participant_count ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                            {{ $approved }} / {{ $group->participant_count }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <p class="text-sm font-semibold text-gray-900">₱{{ number_format($group->total_due, 2) }}</p>
                        @if($group->group_discount_percentage > 0)
                        <p class="text-xs text-green-600">
                            −{{ rtrim(rtrim(number_format($group->group_discount_percentage, 2), '0'), '.') }}%
                        </p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @include('admin.registration_groups._payment_badge', ['status' => $group->payment_status])
                        @if($group->hasPaymentDiscrepancy())
                        <p class="text-xs text-amber-600 mt-1">Amount differs</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $group->created_at->format('M j, Y') }}
                        <span class="block text-xs text-gray-400">{{ $group->created_at->format('g:i A') }}</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.registration-groups.show', $group) }}"
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-sm">No group registrations yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($groups->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $groups->links() }}
    </div>
    @endif
</div>

@endsection

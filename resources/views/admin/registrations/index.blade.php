@extends('layouts.admin')
@section('title', 'Registrations')

@section('content')

{{-- Filters --}}
<form method="GET" action="{{ route('admin.registrations.index') }}"
    class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3">

    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Category</label>
        <select name="category"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All Categories</option>
            @foreach(\App\Models\RaceCategory::orderBy('name')->get() as $cat)
            <option value="{{ $cat->id }}" {{ request('category') === $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
        <select name="status"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="payment_submitted" {{ request('status') === 'payment_submitted' ? 'selected' : '' }}>Payment Submitted</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>

    <div class="flex-1 min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Sex</label>
        <select name="sex"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All</option>
            <option value="male" {{ request('sex') === 'male'   ? 'selected' : '' }}>Male</option>
            <option value="female" {{ request('sex') === 'female' ? 'selected' : '' }}>Female</option>
        </select>
    </div>

    <div class="flex-1 min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Shirt Size</label>
        <select name="shirt_size"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All Sizes</option>
            @foreach(['XS', 'S', 'M', 'L', 'XL', '2XL'] as $size)
            <option value="{{ $size }}" {{ request('shirt_size') === $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex-1 min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Age Group</label>
        <select name="age_group"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All Ages</option>
            <option value="under20" {{ request('age_group') === 'under20' ? 'selected' : '' }}>Under 20</option>
            <option value="20-29" {{ request('age_group') === '20-29'   ? 'selected' : '' }}>20 – 29</option>
            <option value="30-39" {{ request('age_group') === '30-39'   ? 'selected' : '' }}>30 – 39</option>
            <option value="40-49" {{ request('age_group') === '40-49'   ? 'selected' : '' }}>40 – 49</option>
            <option value="50plus" {{ request('age_group') === '50plus'  ? 'selected' : '' }}>50+</option>
        </select>
    </div>

    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Group</label>
        <select name="group"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">Everyone</option>
            <option value="any" {{ request('group') === 'any' ? 'selected' : '' }}>Group registrations only</option>
            @if(request('group') && request('group') !== 'any')
            <option value="{{ request('group') }}" selected>{{ request('group') }}</option>
            @endif
        </select>
    </div>

    <div class="flex items-center gap-2">
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request('category') || request('status') || request('shirt_size') || request('age_group') || request('sex') || request('group'))
        <a href="{{ route('admin.registrations.index') }}"
            class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Clear
        </a>
        @endif
    </div>

    <a href="{{ route('admin.registrations.export', request()->query()) }}"
        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
        Export CSV
    </a>


</form>
<div class="flex items-center justify-between gap-4 mb-4 px-4">
    <span class="text-sm text-gray-900">
        {{ $registrations->total() }} result{{ $registrations->total() !== 1 ? 's' : '' }}
    </span>

    {{-- Group registrations are transactions rather than people, so they get their
         own screen rather than a filter on this list. --}}
    <a href="{{ route('admin.registration-groups.index') }}"
        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 text-sm font-medium rounded-lg hover:bg-amber-100 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        Group Registrations
    </a>
</div>
{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
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
            @endphp
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('last_name') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Participant {!! $sortIcon('last_name') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('status') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Status {!! $sortIcon('status') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('bib_number') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Bib # {!! $sortIcon('bib_number') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Registered {!! $sortIcon('created_at') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($registrations as $reg)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-gray-900">{{ $reg->last_name }}, {{ $reg->first_name }}</p>
                                @if($reg->group?->isGroup())
                                <a href="{{ route('admin.registrations.index', ['group' => $reg->group->reference_code]) }}"
                                    title="Group of {{ $reg->group->participant_count }} — {{ $reg->group->reference_code }}"
                                    class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-amber-50 border border-amber-200 text-amber-700 text-[10px] font-semibold rounded hover:bg-amber-100 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $reg->group->participant_count }}
                                </a>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $reg->email }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-sm text-gray-700">{{ $reg->raceCategory?->name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4">
                        @include('admin.registrations._status_badge', ['status' => $reg->status])
                    </td>
                    <td class="px-5 py-4">
                        @if($reg->bib_number)
                        <span class="inline-flex items-center justify-center h-7 px-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold rounded-md">
                            {{ $reg->formatted_bib }}
                        </span>
                        @else
                        <span class="text-gray-300 text-sm">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $reg->created_at->format('M j, Y') }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.registrations.show', $reg) }}"
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
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm">No registrations found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($registrations->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $registrations->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
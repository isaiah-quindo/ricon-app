@extends('layouts.admin')
@section('title', 'Discount Codes')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-gray-500">{{ $codes->count() }} {{ Str::plural('code', $codes->count()) }}</p>
    </div>
    <div class="flex items-center gap-3">
        {{-- Status filter --}}
        <div class="inline-flex rounded-lg border border-gray-200 bg-white p-0.5 text-xs font-medium">
            @foreach(['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $key => $label)
                <a href="{{ route('admin.discount-codes.index', ['status' => $key]) }}"
                   class="px-3 py-1.5 rounded-md transition-colors {{ $status === $key ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('admin.discount-codes.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Code
        </a>
    </div>
</div>

@if($status === 'inactive')
<div class="mb-4 flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-900">
    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
    </svg>
    <div>
        <p class="font-semibold">Inactive codes are retained for audit.</p>
        <p class="text-amber-800 mt-0.5">To permanently remove a code, review the list here and delete manually via SQL.</p>
    </div>
</div>
@endif

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Race Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Uses</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Expires</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Per Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($codes as $code)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-mono font-semibold text-gray-900">{{ $code->code }}</p>
                            @if($code->description)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $code->description }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $code->raceCategory?->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ rtrim(rtrim(number_format($code->discount_percentage, 2), '0'), '.') }}%
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $code->used_count }} / {{ $code->max_uses ?? '∞' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        @if($code->expires_at)
                            <span class="{{ $code->expires_at->isPast() ? 'text-red-600' : '' }}">
                                {{ $code->expires_at->format('M j, Y g:i A') }}
                            </span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $code->one_per_email ? 'Yes' : 'No' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($code->is_active)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2.5 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded-full px-2.5 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.discount-codes.edit', $code) }}"
                           class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            <p class="text-sm">No discount codes yet.</p>
                            <a href="{{ route('admin.discount-codes.create') }}"
                               class="text-indigo-600 hover:underline text-sm font-medium">
                                Create the first code →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

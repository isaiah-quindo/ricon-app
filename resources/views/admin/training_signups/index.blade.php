@extends('layouts.admin')
@section('title', 'Signups')

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
@endphp

{{-- Tabs --}}
<div class="flex items-center gap-1 border-b border-gray-200 mb-5">
    <a href="{{ route('admin.training-signups.index') }}"
        class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors
              {{ $tab === 'training' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        Training Program
    </a>
    <a href="{{ route('admin.training-signups.index', ['tab' => 'quiz']) }}"
        class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors
              {{ $tab === 'quiz' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        21K Quiz
    </a>
</div>

@if($tab === 'quiz')

{{-- Filters --}}
<form method="GET" action="{{ route('admin.training-signups.index') }}"
    class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3">

    <input type="hidden" name="tab" value="quiz">

    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
    </div>

    <div class="flex-1 min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Result</label>
        <select name="result"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All Results</option>
            <option value="a" {{ request('result') === 'a' ? 'selected' : '' }}>Ready for 21K</option>
            <option value="b" {{ request('result') === 'b' ? 'selected' : '' }}>Almost there</option>
            <option value="c" {{ request('result') === 'c' ? 'selected' : '' }}>Go 10K first</option>
        </select>
    </div>

    <div class="flex items-center gap-2">
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request('result') || request('search'))
        <a href="{{ route('admin.training-signups.index', ['tab' => 'quiz']) }}"
            class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Clear
        </a>
        @endif
    </div>

    <a href="{{ route('admin.training-signups.export', request()->query() + ['tab' => 'quiz']) }}"
        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
        Export CSV
    </a>

</form>
<div class="flex items-center justify-end mb-4 px-4">
    <span class="text-sm text-gray-900">
        {{ $leads->total() }} result{{ $leads->total() !== 1 ? 's' : '' }} ({{ $total }} total)
    </span>
</div>

{{-- Quiz leads table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('first_name') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Name {!! $sortIcon('first_name') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('score') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Score {!! $sortIcon('score') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Result</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Signed Up {!! $sortIcon('created_at') !!}
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($leads as $lead)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $lead->first_name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $lead->email }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2 py-1 bg-gray-50 border border-gray-200 text-gray-600 text-xs font-medium rounded-md">
                            {{ $lead->source === \App\Models\QuizLead::SOURCE_21K_QUIZ ? '21K Page Quiz' : $lead->source }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $lead->score }} / 15
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2 py-1 border text-xs font-bold rounded-md
                            {{ ['a' => 'bg-green-50 border-green-200 text-green-700', 'b' => 'bg-amber-50 border-amber-200 text-amber-700', 'c' => 'bg-indigo-50 border-indigo-200 text-indigo-700'][$lead->result] ?? 'bg-gray-50 border-gray-200 text-gray-600' }}">
                            {{ $lead->result_label }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $lead->created_at->format('M j, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm">No quiz leads yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leads->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $leads->links() }}
    </div>
    @endif
</div>

@else

{{-- Filters --}}
<form method="GET" action="{{ route('admin.training-signups.index') }}"
    class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3">

    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
    </div>

    <div class="flex-1 min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Plan</label>
        <select name="plan"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All Plans</option>
            <option value="tgc100k" {{ request('plan') === 'tgc100k' ? 'selected' : '' }}>100K</option>
            <option value="tgc60k" {{ request('plan') === 'tgc60k' ? 'selected' : '' }}>60K</option>
        </select>
    </div>

    <div class="flex-1 min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Registered for TGC</label>
        <select name="registered_tgc"
            class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">All</option>
            <option value="1" {{ request('registered_tgc') === '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ request('registered_tgc') === '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="flex items-center gap-2">
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request('plan') || request()->filled('registered_tgc') || request('search'))
        <a href="{{ route('admin.training-signups.index') }}"
            class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Clear
        </a>
        @endif
    </div>

    <a href="{{ route('admin.training-signups.export', request()->query()) }}"
        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
        Export CSV
    </a>

</form>
<div class="flex items-center justify-between mb-4 px-4">
    <span class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
        <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold rounded-md">
            Program is on Week {{ \App\Models\TrainingSignup::currentProgramWeek() }} / {{ \App\Models\TrainingSignup::TOTAL_WEEKS }}
        </span>
    </span>
    <span class="text-sm text-gray-900">
        {{ $signups->total() }} result{{ $signups->total() !== 1 ? 's' : '' }} ({{ $total }} total)
    </span>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('first_name') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Name {!! $sortIcon('first_name') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Registered</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('started_on') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Joined {!! $sortIcon('started_on') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            Signed Up {!! $sortIcon('created_at') !!}
                        </a>
                    </th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($signups as $signup)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $signup->first_name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $signup->email }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center justify-center h-7 px-2 {{ $signup->plan === 'tgc100k' ? 'bg-orange-50 border-orange-200 text-orange-700' : 'bg-indigo-50 border-indigo-200 text-indigo-700' }} border text-xs font-bold rounded-md">
                            {{ $signup->plan === 'tgc100k' ? '100K' : '60K' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($signup->registered_tgc)
                        <span class="inline-flex items-center px-2 py-1 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-md">Yes</span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium rounded-md">Not yet</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $signup->started_on->format('M j, Y') }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $signup->created_at->format('M j, Y') }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <form method="POST" action="{{ route('admin.training-signups.resendLink', $signup) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                Resend Link
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm">No training signups yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($signups->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $signups->links() }}
    </div>
    @endif
</div>

@endif

@endsection

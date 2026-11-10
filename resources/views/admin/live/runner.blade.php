@extends('layouts.admin')
@use('App\Support\LiveTracking\Format')
@section('title', $runner['name'] . ' · Times')

@section('content')

@push('head')
    @include('live.partials.fonts')
@endpush

@php
    $slug = $race->course['slug'];
    // datetime-local inputs, because a 100K runs overnight and a bare time can't say which day.
    $local = fn ($time) => $time?->format('Y-m-d\TH:i');
@endphp

<div class="mb-6">
    <a href="{{ route('admin.live.index', $slug) }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Live Tracking
    </a>
</div>

@if ($errors->any())
    <div class="p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800" role="alert">
        Some times couldn't be saved. {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('admin.live.runner.update', [$slug, $runner['bib']]) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf
    @method('PUT')

    <div class="lg:col-span-2 space-y-6">
        {{-- Header card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-xl font-bold text-gray-900">{{ $runner['name'] }}</h1>
                        @include('admin.live._race_status_badge', ['status' => $runner['status']])
                    </div>
                    <p class="text-sm text-gray-500"><span class="font-mono">{{ $runner['bib'] }}</span> · {{ $race->course['title'] }} · {{ $runner['category'] }}</p>
                </div>
                <a href="{{ route('live.runner', [$slug, $runner['bib']]) }}" target="_blank" rel="noopener"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Public page <span aria-hidden="true">↗</span><span class="sr-only"> (opens in a new tab)</span></a>
            </div>
        </div>

        {{-- Split times --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Checkpoint times</h2>
                <p class="text-xs text-gray-500">Times are race-day local time (UTC+8). Clear a field to remove that time.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Checkpoint</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">In</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Out</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rank</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($checkpoints as $c => $cp)
                            @php $pass = $runner['passes'][$c] ?? null; @endphp
                            <tr>
                                <th scope="row" class="px-5 py-3 text-left font-normal whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-orange-700">{{ $cp['label'] }}</span>
                                    <span class="font-medium text-gray-900 ml-1">{{ $cp['display'] }}</span>
                                    <span class="block font-mono text-xs text-gray-400">{{ Format::km($cp['km']) }}</span>
                                </th>
                                <td class="px-3 py-3">
                                    <label for="in-{{ $cp['code'] }}" class="sr-only">{{ $cp['display'] }} in time</label>
                                    <input id="in-{{ $cp['code'] }}" name="passes[{{ $cp['code'] }}][in]" type="datetime-local"
                                        value="{{ old("passes.{$cp['code']}.in", $local($pass['in'] ?? null)) }}"
                                        class="w-52 rounded-lg border border-gray-200 bg-white font-mono text-xs text-gray-700 px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </td>
                                <td class="px-3 py-3">
                                    @unless ($cp['is_finish'])
                                        <label for="out-{{ $cp['code'] }}" class="sr-only">{{ $cp['display'] }} out time</label>
                                        <input id="out-{{ $cp['code'] }}" name="passes[{{ $cp['code'] }}][out]" type="datetime-local"
                                            value="{{ old("passes.{$cp['code']}.out", $local($pass['out'] ?? null)) }}"
                                            class="w-52 rounded-lg border border-gray-200 bg-white font-mono text-xs text-gray-700 px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    @endunless
                                </td>
                                <td class="px-5 py-3 font-mono text-gray-700">{{ $pass ? Format::ordinal($pass['rank']) : '–' }}</td>
                            </tr>
                            @if ($errors->has("passes.{$cp['code']}.*"))
                                <tr>
                                    <td colspan="4" class="px-5 pb-3 text-xs text-red-600">{{ $errors->first("passes.{$cp['code']}.*") }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Race status --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4"
            x-data="{ status: @js(old('status', $runner['status'] === 'dnf' ? 'dnf' : 'racing')) }">
            <h2 class="font-semibold text-gray-900">Race status</h2>
            <fieldset>
                <legend class="sr-only">Race status</legend>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" name="status" value="racing" x-model="status" class="text-indigo-600 focus:ring-indigo-500"> In the race
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" name="status" value="dnf" x-model="status" class="text-indigo-600 focus:ring-indigo-500"> Did not finish (DNF)
                    </label>
                </div>
            </fieldset>
            <div x-show="status === 'dnf'" x-cloak class="space-y-3">
                <div>
                    <label for="dnf-at" class="block text-xs font-medium text-gray-500 mb-1.5">Dropped at</label>
                    <select id="dnf-at" name="dnf_checkpoint"
                        class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @foreach ($checkpoints as $c => $cp)
                            @continue($cp['is_finish'])
                            <option value="{{ $cp['code'] }}" @selected(old('dnf_checkpoint', $checkpoints[$runner['dnf']['index'] ?? $runner['last'] ?? 0]['code']) === $cp['code'])>{{ $cp['label'] }} · {{ $cp['display'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="dnf-reason" class="block text-xs font-medium text-gray-500 mb-1.5">Reason</label>
                    <select id="dnf-reason" name="dnf_reason"
                        class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @foreach ($reasons as $reason)
                            <option @selected(old('dnf_reason', $runner['dnf']['reason'] ?? null) === $reason)>{{ $reason }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Save corrections
            </button>
        </div>
    </div>
</form>

@endsection

@extends('layouts.admin')
@use('App\Support\LiveTracking\Format')
@section('title', 'Live Tracking')

@section('content')

@include('admin.live._header', ['title' => 'Live Tracking'])

@php $stats = $race->stats(); @endphp

{{-- Stats cards (same layout as the dashboard) --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach ([
        ['On course', $stats['on_course'], 'Still racing'],
        ['At aid stations', $rows->sum('at_station'), 'Arrived, not yet out'],
        ['Finished', $stats['finished'], 'Crossed the line'],
        ['DNF', $stats['dnf'], 'Withdrew or missed a cutoff'],
    ] as [$label, $value, $hint])
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <span class="text-sm font-medium text-gray-500">{{ $label }}</span>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($value) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $hint }}</p>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Checkpoints --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Checkpoints</h2>
            <p class="text-xs text-gray-500">Overdue: runners whose arrival window has already passed.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Checkpoint</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Through</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">There now</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Overdue</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">DNF</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cutoff</th>
                        <th class="px-5 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($rows as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-orange-700">{{ $row['cp']['label'] }}</span>
                                <span class="font-medium text-gray-900 ml-1">{{ $row['cp']['display'] }}</span>
                                <span class="block font-mono text-xs text-gray-400">{{ Format::km($row['cp']['km']) }}</span>
                            </td>
                            <td class="px-5 py-3 text-right font-mono text-gray-900">{{ $row['through'] }}</td>
                            <td class="px-5 py-3 text-right font-mono {{ $row['at_station'] ? 'text-orange-700 font-semibold' : 'text-gray-400' }}">{{ $row['at_station'] }}</td>
                            <td class="px-5 py-3 text-right font-mono {{ $row['overdue'] ? 'text-amber-700 font-semibold' : 'text-gray-400' }}">{{ $row['overdue'] }}</td>
                            <td class="px-5 py-3 text-right font-mono {{ $row['dnf'] ? 'text-red-700 font-semibold' : 'text-gray-400' }}">{{ $row['dnf'] }}</td>
                            <td class="px-5 py-3 whitespace-nowrap font-mono text-xs text-gray-600">{{ $row['cp']['cutoff'] ? Format::timeWithDay($row['cp']['cutoff']) : 'None' }}</td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.live.timing', [$race->course['slug'], 'cp' => $row['cp']['code']]) }}"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Record times</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('admin.live._race_clock')
</div>

{{-- Runners --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden"
    x-data="{ query: '', matches(text) { return text.includes(this.query.trim().toLowerCase()) } }">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-semibold text-gray-900">Runners</h2>
            <p class="text-xs text-gray-500">Current position order. Runners who dropped out are at the bottom.</p>
        </div>
        <div class="w-full sm:w-64">
            {{-- No visible label, so aria-label names it for screen readers. --}}
            <input type="search" x-model="query" placeholder="Name or bib" aria-label="Find a runner by name or bib"
                class="w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pos</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Runner</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last checkpoint</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Next ETA</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($runners as $runner)
                    @php
                        $last = $runner['last'] !== null ? $checkpoints[$runner['last']] : null;
                        $eta = $race->eta($runner);
                    @endphp
                    <tr class="hover:bg-gray-50" x-data="{ confirming: false }"
                        x-show="matches(@js(Str::lower($runner['name'] . ' ' . $runner['bib'])))">
                        <td class="px-5 py-3 font-mono text-gray-900">{{ $runner['position'] ?? '–' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <a href="{{ route('admin.live.runner', [$race->course['slug'], $runner['bib']]) }}" class="font-medium text-gray-900 hover:text-indigo-600">{{ $runner['name'] }}</a>
                            <span class="block font-mono text-xs text-gray-500">{{ $runner['bib'] }} · {{ $runner['category'] }}</span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @if ($last)
                                <span class="text-gray-900">{{ $last['label'] }} {{ $last['display'] }}</span>
                                <span class="block font-mono text-xs text-gray-500">{{ Format::time($runner['passes'][$runner['last']]['in']) }}</span>
                            @else
                                <span class="text-gray-400">Start</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @if ($eta)
                                <span class="font-mono text-gray-900">{{ Format::window($eta) }}</span>
                                @if ($eta['overdue'])
                                    <span class="block text-xs font-semibold text-amber-700">Overdue</span>
                                @endif
                            @else
                                <span class="text-gray-400">–</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @include('admin.live._race_status_badge', ['status' => $runner['status']])
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.live.runner', [$race->course['slug'], $runner['bib']]) }}"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-800 mr-3">Edit times</a>
                            @php $dnfRoute = route('admin.live.runner.dnf', [$race->course['slug'], $runner['bib']]); @endphp
                            @if (in_array($runner['status'], ['on_course', 'at_station'], true))
                                {{-- Two-step so a mis-tap on race day can't drop someone from the race. --}}
                                <button type="button" x-show="!confirming" @click="confirming = true"
                                    class="text-sm font-medium text-red-600 hover:text-red-800">Mark DNF</button>
                                <form x-show="confirming" x-cloak method="POST" action="{{ $dnfRoute }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    <button type="submit"
                                        class="px-2.5 py-1 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700">Confirm DNF</button>
                                    <button type="button" @click="confirming = false"
                                        class="text-xs font-medium text-gray-500 hover:text-gray-800">Cancel</button>
                                </form>
                            @elseif ($runner['status'] === 'dnf')
                                <form method="POST" action="{{ $dnfRoute }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-800">Undo DNF</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">
                            No approved runners with a bib in {{ $race->course['title'] }} yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@use('App\Support\LiveTracking\Eta')
@use('App\Support\LiveTracking\Format')
@props(['eta', 'empty' => null])

{{-- Always a window, never one time. Before a runner has passed 2 checkpoints there is no estimate. --}}
@if ($eta)
    <span {{ $attributes->merge(['class' => 'font-mono tabular-nums']) }}>{{ Format::window($eta) }}</span>
    @if ($eta['overdue'])
        <span class="ml-1 text-xs font-semibold text-amber-300">Overdue</span>
    @endif
@else
    <span class="text-sm text-gray-400">{{ $empty ?? 'Estimate after ' . Eta::MIN_CHECKPOINTS . ' checkpoints' }}</span>
@endif

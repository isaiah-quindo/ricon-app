@use('App\Support\LiveTracking\Format')
@props(['time', 'now' => null])

{{-- A clock time, with "X min ago" under it when $now is given. --}}
<time datetime="{{ $time->toIso8601String() }}" {{ $attributes->merge(['class' => 'block']) }}>
    <span class="block font-mono tabular-nums text-white">{{ Format::time($time) }}</span>
    @if ($now)
        <span class="block text-xs text-gray-400">{{ Format::ago($time, $now) }}</span>
    @endif
</time>

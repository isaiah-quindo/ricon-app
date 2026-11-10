@props(['state', 'label' => null])

@php
    // [pill classes, dot classes, default label]. Same idea as admin/registrations/_status_badge.
    [$classes, $dot, $default] = match ($state) {
        'at_station' => ['border-orange-500/40 bg-orange-500/15 text-orange-300', 'bg-orange-400 motion-safe:animate-pulse', 'At aid station'],
        'finished'   => ['border-emerald-500/30 bg-emerald-500/10 text-emerald-300', 'bg-emerald-400', 'Finished'],
        'dnf'        => ['border-red-500/30 bg-red-500/10 text-red-300', 'bg-red-400', 'DNF'],
        'on_course'  => ['border-white/10 bg-white/5 text-gray-200', 'bg-sky-400', 'On course'],
        'not_started' => ['border-white/10 bg-white/5 text-gray-300', 'bg-gray-500', 'Not started'],
        default      => ['border-white/10 bg-white/5 text-gray-300', 'bg-gray-500', 'Departed'],
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border px-2.5 py-1 text-xs font-medium {$classes}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dot }}" aria-hidden="true"></span>
    {{ $label ?? $default }}
</span>

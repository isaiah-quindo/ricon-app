@php
    $styles = [
        'verified' => ['bg-green-50 border-green-200 text-green-700', 'Paid'],
        'rejected' => ['bg-red-50 border-red-200 text-red-700', 'Rejected'],
        'pending'  => ['bg-amber-50 border-amber-200 text-amber-700', 'Awaiting payment'],
    ];
    [$classes, $label] = $styles[$status] ?? $styles['pending'];
@endphp
<span class="inline-flex items-center px-2 py-0.5 border text-xs font-semibold rounded {{ $classes }}">
    {{ $label }}
</span>

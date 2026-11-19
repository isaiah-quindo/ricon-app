@php
$config = match($status) {
    'payment_submitted' => ['bg-blue-50 border-blue-200 text-blue-700', 'bg-blue-500'],
    'confirmed'         => ['bg-indigo-50 border-indigo-200 text-indigo-700', 'bg-indigo-500'],
    'shipped'           => ['bg-orange-50 border-orange-200 text-orange-700', 'bg-orange-500'],
    'completed'         => ['bg-green-50 border-green-200 text-green-700', 'bg-green-500'],
    'rejected'          => ['bg-red-50 border-red-200 text-red-700', 'bg-red-500'],
    'cancelled'         => ['bg-gray-100 border-gray-200 text-gray-600', 'bg-gray-400'],
    'pending'           => ['bg-amber-50 border-amber-200 text-amber-700', 'bg-amber-500'],
    default             => ['bg-gray-100 border-gray-200 text-gray-600', 'bg-gray-400'],
};
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border whitespace-nowrap {{ $config[0] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config[1] }}"></span>
    {{ \App\Models\Order::STATUSES[$status] ?? ucfirst($status) }}
</span>

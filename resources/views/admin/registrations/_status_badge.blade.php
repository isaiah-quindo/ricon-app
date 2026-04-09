@php
$config = match($status) {
    'approved'          => ['bg-green-50 border-green-200 text-green-700', 'bg-green-500', 'Approved'],
    'rejected'          => ['bg-red-50 border-red-200 text-red-700', 'bg-red-500', 'Rejected'],
    'payment_submitted' => ['bg-amber-50 border-amber-200 text-amber-700', 'bg-amber-500', 'Payment Submitted'],
    default             => ['bg-gray-100 border-gray-200 text-gray-600', 'bg-gray-400', 'Pending'],
};
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $config[0] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config[1] }}"></span>
    {{ $config[2] }}
</span>

@php
// Same pattern as admin/registrations/_status_badge, for a runner's race status.
$config = match($status) {
    'at_station' => ['bg-orange-50 border-orange-200 text-orange-800', 'bg-orange-500', 'At aid station'],
    'on_course'  => ['bg-blue-50 border-blue-200 text-blue-700', 'bg-blue-500', 'On course'],
    'finished'   => ['bg-green-50 border-green-200 text-green-700', 'bg-green-500', 'Finished'],
    'dnf'        => ['bg-red-50 border-red-200 text-red-700', 'bg-red-500', 'DNF'],
    'not_started' => ['bg-gray-100 border-gray-200 text-gray-600', 'bg-gray-400', 'Not started'],
    default      => ['bg-gray-100 border-gray-200 text-gray-600', 'bg-gray-400', 'Unknown'],
};
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border whitespace-nowrap {{ $config[0] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config[1] }}"></span>
    {{ $config[2] }}
</span>

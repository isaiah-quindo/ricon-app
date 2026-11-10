@props(['change'])

{{--
    Positive = moved up. The arrow and number carry the meaning, so it never relies on colour;
    the arrow is hidden from screen readers, which hear the sr-only sentence instead.
--}}
@if ($change === null)
    <span class="text-xs text-gray-500"><span aria-hidden="true">–</span><span class="sr-only">First checkpoint</span></span>
@elseif ($change > 0)
    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-sky-400">
        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 3.5a.75.75 0 0 1 .53.22l5 5a.75.75 0 1 1-1.06 1.06L10.75 6.06V16a.75.75 0 0 1-1.5 0V6.06L5.53 9.78a.75.75 0 0 1-1.06-1.06l5-5A.75.75 0 0 1 10 3.5Z" clip-rule="evenodd" /></svg>
        <span aria-hidden="true">{{ $change }}</span>
        <span class="sr-only">Up {{ $change }} {{ Str::plural('place', $change) }}</span>
    </span>
@elseif ($change < 0)
    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-gray-300">
        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 16.5a.75.75 0 0 1-.53-.22l-5-5a.75.75 0 1 1 1.06-1.06l3.72 3.72V4a.75.75 0 0 1 1.5 0v9.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-5 5a.75.75 0 0 1-.53.22Z" clip-rule="evenodd" /></svg>
        <span aria-hidden="true">{{ abs($change) }}</span>
        <span class="sr-only">Down {{ abs($change) }} {{ Str::plural('place', abs($change)) }}</span>
    </span>
@else
    <span class="text-xs text-gray-400">No change</span>
@endif

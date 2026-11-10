{{-- Black text on orange-500 is about 7:1. White on orange-500 would fail contrast (2.8:1). --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full bg-orange-500 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-black']) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-black motion-safe:animate-pulse" aria-hidden="true"></span>
    Live
</span>

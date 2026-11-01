{{--
    Route description excerpt with a "Read more" modal for the full text.
    Expects: $slug (key in config/route-descriptions.php), $title, $accent (text color class)
--}}
@php
$description = config("route-descriptions.$slug");
$excerpt = array_slice($description['paragraphs'], 0, $description['excerpt']);
@endphp

<div x-data="{ open: false }" @keydown.escape.window="open = false"
    x-effect="document.body.classList.toggle('overflow-hidden', open)">
    @foreach($excerpt as $paragraph)
    <p class="text-gray-400 leading-relaxed mb-4">{{ $paragraph }}</p>
    @endforeach

    <button type="button" @click="open = true"
        class="inline-flex items-center gap-x-1.5 text-sm font-bold {{ $accent }} hover:underline underline-offset-4">
        Read the full route description
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    </button>

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
            role="dialog" aria-modal="true" aria-labelledby="{{ $slug }}-route-title">
            <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/80" @click="open = false"></div>

            <div x-show="open" x-transition
                class="relative w-full max-w-2xl max-h-[85vh] flex flex-col bg-[#111111] border border-white/10 rounded-2xl shadow-xl">
                <div class="flex items-start justify-between gap-4 px-6 md:px-8 pt-6 pb-4 border-b border-white/10">
                    <div>
                        <p class="{{ $accent }} text-xs font-semibold uppercase tracking-wider mb-1">Route Description</p>
                        <h3 id="{{ $slug }}-route-title" class="text-2xl font-bold text-white">{{ $title }}</h3>
                    </div>
                    <button type="button" @click="open = false"
                        class="shrink-0 w-9 h-9 inline-flex items-center justify-center rounded-full text-gray-400 hover:text-white hover:bg-white/10"
                        aria-label="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-6 md:px-8 py-6 space-y-4">
                    @foreach($description['paragraphs'] as $paragraph)
                    <p class="text-gray-300 leading-relaxed">{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </template>
</div>

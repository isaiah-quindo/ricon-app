@extends('layouts.public')
@section('title', "TGC100 Runner's Handbook")
@section('og_title', "Runner's Handbook | The Great Cordillera 100 Ultra Trail")
@section('og_description', "The official TGC100 Runner's Handbook. Read it online or download the PDF before race week.")

@php
    $pdf = asset('docs/tgc100-runners-handbook.pdf');
@endphp

@section('content')
{{-- ======================================================== --}}
{{-- HERO --}}
{{-- ======================================================== --}}
<section class="relative min-h-[40vh] flex items-end overflow-hidden pt-32">
    <div class="absolute inset-0 bg-gray-900"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-black/60 to-transparent"></div>
    <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100 &middot; Race Info</p>
        <h1 class="text-5xl md:text-6xl font-black text-white leading-tight">
            Runner's Handbook
        </h1>
        <p class="text-gray-400 mt-3 text-lg max-w-3xl">Everything you need to know for race week, in one document. Read it here or download a copy to keep on your phone.</p>

        <div class="flex flex-wrap items-center gap-4 mt-8">
            <a href="{{ $pdf }}" download class="inline-flex items-center gap-x-2 py-3 px-5 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                Download Handbook
            </a>
            <span class="text-gray-500 text-sm">PDF &middot; 48 pages &middot; 1.7 MB</span>
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- PDF VIEWER --}}
{{-- ======================================================== --}}
<section class="bg-[#0d0d0d] py-16 border-t border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">

        {{-- Rendered with PDF.js rather than an <iframe>: production sends X-Frame-Options: DENY, which blocks embedding the PDF --}}
        <div id="handbook-viewer" class="rounded-2xl overflow-hidden border border-white/10 bg-[#111111]">
            <div class="flex items-center justify-between gap-4 px-4 h-12 border-b border-white/10 text-sm">
                <span class="text-gray-400">Page <span data-page class="text-white font-semibold">1</span> of <span data-total>48</span></span>
                <a href="{{ $pdf }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-white transition-colors">Open in new tab</a>
            </div>
            {{-- One A4 page tall (595x842) at full width; pages scroll inside --}}
            <div data-scroll class="overflow-y-auto bg-[#1a1a1a]" style="aspect-ratio:595/842;">
                <div data-status class="h-full flex items-center justify-center px-6 text-center text-gray-500 text-sm">Loading handbook...</div>
                <div data-pages class="flex flex-col gap-2"></div>
            </div>
        </div>

        <noscript>
            <p class="text-gray-500 text-sm mt-4 text-center">
                The viewer needs JavaScript. <a href="{{ $pdf }}" target="_blank" rel="noopener" class="text-orange-500 underline decoration-orange-500/30 underline-offset-2 hover:decoration-orange-500">Open the handbook in a new tab</a>.
            </p>
        </noscript>
    </div>
</section>
@endsection

@push('scripts')
<script type="module">
    import * as pdfjsLib from 'https://cdn.jsdelivr.net/npm/pdfjs-dist@6.4.299/legacy/build/pdf.min.mjs';
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@6.4.299/legacy/build/pdf.worker.min.mjs';

    const root = document.getElementById('handbook-viewer');
    const scroller = root.querySelector('[data-scroll]');
    const pagesEl = root.querySelector('[data-pages]');
    const status = root.querySelector('[data-status]');
    const pageLabel = root.querySelector('[data-page]');
    const PDF_URL = @json($pdf);
    const GAP = 8;

    try {
        const pdf = await pdfjsLib.getDocument({ url: PDF_URL }).promise;
        root.querySelector('[data-total]').textContent = pdf.numPages;

        // Every page in the handbook is A4, so size all slots from page 1
        const first = (await pdf.getPage(1)).getViewport({ scale: 1 });
        const slots = [];
        for (let n = 1; n <= pdf.numPages; n++) {
            const slot = document.createElement('div');
            slot.className = 'relative bg-white';
            slot.style.aspectRatio = `${first.width} / ${first.height}`;
            const canvas = document.createElement('canvas');
            canvas.className = 'absolute inset-0 w-full h-full';
            canvas.setAttribute('aria-label', `Handbook page ${n}`);
            slot.appendChild(canvas);
            pagesEl.appendChild(slot);
            slots.push({ n, slot, canvas, width: 0, task: null });
        }
        status.remove();

        // Draw a page at the slot's current width (sharp on retina, capped at 2x to save memory)
        async function render(s) {
            const width = s.slot.clientWidth;
            if (!width || s.width === width) return;
            s.width = width;
            s.task?.cancel();
            const page = await pdf.getPage(s.n);
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            const viewport = page.getViewport({ scale: (width / page.getViewport({ scale: 1 }).width) * dpr });
            s.canvas.width = viewport.width;
            s.canvas.height = viewport.height;
            s.task = page.render({ canvas: s.canvas, canvasContext: s.canvas.getContext('2d'), viewport });
            try { await s.task.promise; } catch (e) { if (e?.name !== 'RenderingCancelledException') throw e; }
        }

        // Only draw pages near the viewport
        const visible = new Set();
        const io = new IntersectionObserver((entries) => {
            for (const entry of entries) {
                const s = slots[Number(entry.target.dataset.index)];
                if (entry.isIntersecting) { visible.add(s); render(s); } else { visible.delete(s); }
            }
        }, { root: scroller, rootMargin: '100% 0px' });
        slots.forEach((s, i) => { s.slot.dataset.index = i; io.observe(s.slot); });

        scroller.addEventListener('scroll', () => {
            const pageHeight = slots[0].slot.offsetHeight + GAP;
            const current = Math.floor((scroller.scrollTop + scroller.clientHeight / 2) / pageHeight) + 1;
            pageLabel.textContent = Math.min(Math.max(current, 1), slots.length);
        }, { passive: true });

        // Redraw visible pages when the viewer is resized
        let resizeTimer;
        new ResizeObserver(() => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => visible.forEach(render), 200);
        }).observe(scroller);
    } catch (e) {
        console.error(e);
        status.innerHTML = `Couldn't load the viewer. <a href="${PDF_URL}" target="_blank" rel="noopener" class="ml-1 text-orange-500 underline">Open the handbook in a new tab</a>.`;
    }
</script>
@endpush

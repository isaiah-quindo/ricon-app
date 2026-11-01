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

        {{-- Desktop: inline viewer --}}
        {{-- Height follows the A4 page ratio (842/595) of the container width so one full page shows at fit-width; +56px covers the viewer toolbar --}}
        <div class="hidden md:block rounded-2xl overflow-hidden border border-white/10 bg-[#111111]">
            <iframe src="{{ $pdf }}#view=FitH&pagemode=none" title="TGC100 Runner's Handbook" class="block w-full" style="height:calc((min(100vw, 1280px) - 64px) * 1.4146 + 56px);" loading="lazy"></iframe>
        </div>

        {{-- Mobile: browsers don't render embedded PDFs reliably, so open it natively instead --}}
        <div class="md:hidden rounded-2xl border border-white/10 bg-[#111111] p-6 text-center">
            <svg class="w-10 h-10 mx-auto text-orange-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-white font-bold mb-1">TGC100 Runner's Handbook</p>
            <p class="text-gray-400 text-sm mb-6">Open the handbook in your phone's PDF viewer, or save a copy for offline reading.</p>
            <div class="flex flex-col gap-3">
                <a href="{{ $pdf }}" target="_blank" rel="noopener" class="inline-flex justify-center items-center gap-x-2 py-3 px-5 text-sm font-bold rounded-lg border border-white/20 text-white hover:bg-white/5 transition-colors">
                    Read Handbook
                </a>
                <a href="{{ $pdf }}" download class="inline-flex justify-center items-center gap-x-2 py-3 px-5 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                    Download Handbook
                </a>
            </div>
        </div>

        <p class="hidden md:block text-gray-500 text-sm mt-4 text-center">
            Viewer not loading? <a href="{{ $pdf }}" target="_blank" rel="noopener" class="text-orange-500 underline decoration-orange-500/30 underline-offset-2 hover:decoration-orange-500">Open the handbook in a new tab</a>.
        </p>
    </div>
</section>
@endsection

@extends('layouts.public')
@section('title', $runner['name'] . ' · ' . $race->course['title'] . ' Live')
@section('og_title', $runner['name'] . ' · ' . $race->course['title'] . ' Live Tracking')
@section('og_description', 'Follow ' . $runner['name'] . ' live at The Great Cordillera 100: checkpoint times, position and next arrival window.')

@section('head')
    @include('live.partials.fonts')
@endsection

@php $slug = $race->course['slug']; @endphp

@section('content')
{{-- liveRunner (below) refreshes the status, stats, timeline and next-checkpoint card every 30 seconds. --}}
<div class="min-h-screen bg-[#0d0d0d] pb-20 pt-28"
    x-data="liveRunner(@js(route('live.runner.updates', [$slug, $runner['bib']])))">
    <div class="mx-auto px-4 md:px-8" style="max-width:1280px;">

        <a href="{{ route('live.show', $slug) }}"
            class="mb-6 inline-flex min-h-[44px] items-center gap-1.5 rounded-md text-sm text-gray-400 transition-colors hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back to race centre
        </a>

        {{-- ========== Header ========== --}}
        <header class="flex flex-wrap items-start justify-between gap-6">
            <div>
                <div x-ref="status">
                    @include('live.partials.runner-status')
                </div>
                <h1 class="mt-3 text-3xl font-black leading-tight text-white md:text-5xl">{{ $runner['name'] }}</h1>
                <p class="mt-1 text-gray-400">{{ $race->course['title'] }} · {{ $runner['category'] }}</p>
                @include('live.partials.updated-status')
            </div>

            {{-- Toggle button: aria-pressed tells screen readers whether this runner is followed. --}}
            <button type="button" x-data
                @click="$store.follow.toggle(@js($runner['bib']))"
                :aria-pressed="$store.follow.has(@js($runner['bib']))"
                aria-pressed="false"
                class="inline-flex min-h-[44px] items-center gap-2 rounded-lg border border-white/20 px-5 text-sm font-semibold text-white transition-colors hover:border-white/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400 aria-pressed:border-orange-500 aria-pressed:bg-orange-500 aria-pressed:text-black">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" x-show="!$store.follow.has(@js($runner['bib']))" /><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-8 8a1 1 0 0 1-1.4 0l-4-4a1 1 0 1 1 1.4-1.4L8 12.58l7.3-7.3a1 1 0 0 1 1.4 0Z" clip-rule="evenodd" x-show="$store.follow.has(@js($runner['bib']))" x-cloak /></svg>
                <span x-text="$store.follow.has(@js($runner['bib'])) ? 'Following' : 'Follow'">Follow</span>
            </button>
        </header>

        <div class="mt-6" x-ref="stats">
            @include('live.partials.runner-stats')
        </div>

        <div class="mt-10 grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="min-w-0" x-ref="timeline">
                @include('live.partials.timeline')
            </div>
            <aside aria-label="Next checkpoint" x-ref="next">
                @include('live.partials.next-checkpoint-card')
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('live.partials.follow-store', ['slug' => $slug])
@include('live.partials.live-updates-script')
<script>
document.addEventListener('alpine:init', () => {
    // A runner's page changes less often than the race centre, so every 30 seconds is enough.
    Alpine.data('liveRunner', (updatesUrl) => ({
        ...window.liveUpdates(30),

        init() {
            this.startUpdates();
        },

        updatesUrl() {
            return updatesUrl;
        },
    }));
});
</script>
@endpush

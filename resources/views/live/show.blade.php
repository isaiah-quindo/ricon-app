@extends('layouts.public')
@section('title', $race->course['title'] . ' Live')
@section('og_title', $race->course['title'] . ' Live Tracking. The Great Cordillera 100')
@section('og_description', 'Follow TGC100 runners live: the latest checkpoint arrivals, positions and arrival windows for every runner on course.')

@section('head')
    @include('live.partials.fonts')
@endsection

@section('content')
{{--
    Race centre. The first load is rendered by the server like any page. After that,
    raceCentre (below) asks /live/{slug}/updates for fresh data every 20 seconds, and
    again whenever the checkpoint, search or "Show more" changes.
--}}
<div x-data="raceCentre(@js([
        'cp'         => $selected,
        'names'      => collect($checkpoints)->pluck('display', 'code'),
        'expected'   => $expected,
        'runners'    => $followData,
        'counts'     => $counts,
        'leader'     => $leader,
        'matches'    => $matches,
        'limit'      => $limit,
        'page'       => $page,
        'updatesUrl' => route('live.updates', $race->course['slug']),
    ]))"
    class="min-h-screen bg-[#0d0d0d] pb-20 pt-28">
    <div class="mx-auto px-4 md:px-8" style="max-width:1280px;">

        @include('live.partials.header')
        @include('live.partials.checkpoint-selector')

        <div class="mt-10 grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="min-w-0">
                @include('live.partials.arrivals-feed')
            </div>

            <aside class="space-y-6" aria-label="Followed runners and leaders">
                @include('live.partials.following-panel')
                <div x-ref="leaders">
                    @include('live.partials.leaders')
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('live.partials.follow-store', ['slug' => $race->course['slug']])
@include('live.partials.live-updates-script')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('raceCentre', ({ cp, names, expected, runners, counts, leader, matches, limit, page, updatesUrl }) => ({
        ...window.liveUpdates(20),

        cp: cp ?? '',       // '' means "All checkpoints"
        query: '',
        limit,              // rows asked for; "Show more" adds another page of `page` rows
        matches,            // rows that match the checkpoint and search, shown or not
        counts,             // "N through" per checkpoint card
        leader,             // { km, text, percent } for the progress bar
        expected,           // leaders' arrival window for checkpoints nobody has reached
        runners,            // summary of every runner, for the Following panel

        init() {
            this.startUpdates();
            // A shared ?cp= link may point at a card that's off-screen in the scroller.
            if (this.cp) {
                this.$root.querySelector('[role=group] [aria-pressed=true]')
                    ?.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            }
        },

        get cpName() {
            return this.cp ? names[this.cp] : 'All checkpoints';
        },

        get expectedText() {
            return this.expected[this.cp] ?? null;
        },

        // Runners this viewer follows, in the order they followed them.
        get followed() {
            return Alpine.store('follow').bibs.map(bib => this.runners[bib]).filter(Boolean);
        },

        select(code) {
            this.cp = code;
            this.limit = page;
            this.fetchUpdates();

            // Put the choice in the URL (?cp=as6) so the link can be shared, without reloading.
            const url = new URL(window.location);
            code ? url.searchParams.set('cp', code) : url.searchParams.delete('cp');
            history.replaceState(null, '', url);
        },

        search() {
            this.limit = page;
            this.fetchUpdates();
        },

        showMore() {
            this.limit += page;
            this.fetchUpdates();
        },

        updatesUrl() {
            const params = new URLSearchParams({ limit: this.limit });
            if (this.cp) params.set('cp', this.cp);
            if (this.query.trim()) params.set('q', this.query.trim());
            return `${updatesUrl}?${params}`;
        },

        // The parts of the response that aren't HTML.
        applyUpdates(data) {
            this.matches = data.matches;
            this.counts = data.counts;
            this.leader = data.leader;
            this.expected = data.expected;
            this.runners = data.runners;
        },
    }));
});
</script>
@endpush

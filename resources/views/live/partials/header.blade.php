<header>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-orange-500">The Great Cordillera 100</p>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-3xl font-black leading-tight text-white md:text-5xl">{{ $race->course['title'] }}</h1>
                <x-live.live-pill />
            </div>
            @include('live.partials.updated-status')
        </div>
        @include('live.partials.distance-switch')
    </div>

    <div class="mt-6" x-ref="stats">
        @include('live.partials.stats')
    </div>
</header>

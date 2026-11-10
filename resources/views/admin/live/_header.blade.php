@use('App\Support\LiveTracking\Course')
{{-- Shared by the live tracking admin pages: title, race switch and page links. --}}
@push('head')
    @include('live.partials.fonts')
@endpush

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="text-sm text-gray-500">{{ $race->course['title'] }} · now <span class="font-mono">{{ App\Support\LiveTracking\Format::timeWithDay($race->now) }}</span></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        {{-- Race switch keeps you on the same kind of page. --}}
        <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1" role="group" aria-label="Race">
            @foreach (Course::slugs() as $slug)
                <a href="{{ route(Route::currentRouteName() === 'admin.live.timing' ? 'admin.live.timing' : 'admin.live.index', $slug) }}"
                    @if ($slug === $race->course['slug']) aria-current="page" @endif
                    class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ $slug === $race->course['slug'] ? 'bg-gray-900 text-white' : 'text-gray-500 hover:text-gray-900' }}">
                    {{ strtoupper(str_replace('km', 'K', $slug)) }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('admin.live.index', $race->course['slug']) }}"
            class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.live.index') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">Overview</a>
        <a href="{{ route('admin.live.timing', $race->course['slug']) }}"
            class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.live.timing') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">Timing console</a>
        <a href="{{ route('live.show', $race->course['slug']) }}" target="_blank" rel="noopener"
            class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Public page <span aria-hidden="true">↗</span><span class="sr-only"> (opens in a new tab)</span></a>
    </div>
</div>


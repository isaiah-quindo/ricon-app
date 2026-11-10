@use('App\Support\LiveTracking\Course')

{{-- Links, not buttons: each distance is its own page. aria-current marks the one being viewed. --}}
<nav aria-label="Race distance" class="inline-flex rounded-lg border border-white/10 bg-white/5 p-1">
    @foreach (Course::slugs() as $slug)
        @php $current = $slug === $race->course['slug']; @endphp
        <a href="{{ route('live.show', $slug) }}" @if ($current) aria-current="page" @endif
            class="inline-flex min-h-[44px] items-center rounded-md px-4 text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400
                   {{ $current ? 'bg-orange-500 text-black' : 'text-gray-300 hover:text-white' }}">
            {{ strtoupper(str_replace('km', 'K', $slug)) }}
        </a>
    @endforeach
</nav>

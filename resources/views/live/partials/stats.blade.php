@use('App\Support\LiveTracking\Format')
@php $stats = $race->stats(); @endphp

{{-- Race centre stat tiles. Re-rendered by each update. --}}
<dl class="grid grid-cols-2 gap-3 md:grid-cols-4">
    <x-live.stat-tile label="Race time" :value="Format::duration($stats['race_seconds'], true)" mono />
    <x-live.stat-tile label="On course" :value="$stats['on_course']" />
    <x-live.stat-tile label="Finished" :value="$stats['finished']" />
    <x-live.stat-tile label="DNF" :value="$stats['dnf']" />
</dl>

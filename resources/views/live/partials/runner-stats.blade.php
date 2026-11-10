@use('App\Support\LiveTracking\Format')
@php
    $distance = $runner['last'] !== null ? $checkpoints[$runner['last']]['km'] : 0;
    $gender = $runner['sex'] === 'F' ? 'women' : 'men';
@endphp

{{-- Rank, elapsed time and distance on the runner page. Re-rendered by each update. --}}
<dl class="grid grid-cols-2 gap-3 md:grid-cols-4">
    <x-live.stat-tile label="Overall" :value="$runner['position'] ? Format::ordinal($runner['position']) : '–'" />
    <x-live.stat-tile :label="'Among ' . $gender" :value="$runner['gender_position'] ? Format::ordinal($runner['gender_position']) : '–'" />
    <x-live.stat-tile label="Elapsed" :value="Format::duration($race->elapsed($runner), true)" mono />
    <x-live.stat-tile label="Distance" :value="Format::km($distance)" />
</dl>

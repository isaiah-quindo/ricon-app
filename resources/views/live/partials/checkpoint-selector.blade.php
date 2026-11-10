@use('App\Support\LiveTracking\Format')
@php $finishKm = $race->course['finish_km']; @endphp

{{-- Counts and the leader's progress update through Alpine (counts, leader), so the cards
     are never re-rendered and a scrolled or focused card stays where it is. --}}

<section aria-labelledby="checkpoints-heading" class="mt-10">
    <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
        <h2 id="checkpoints-heading" class="text-lg font-bold text-white">Checkpoints</h2>
        <p class="text-xs text-gray-400">Leader at <span class="font-mono text-gray-200" x-text="leader.text">{{ $leader['text'] }}</span> of {{ Format::km($finishKm) }}</p>
    </div>

    {{-- How far the leader has got. Each tick is a checkpoint. --}}
    <div role="progressbar" aria-label="Leader's progress"
        aria-valuemin="0" aria-valuemax="{{ $finishKm }}" aria-valuenow="{{ $leader['km'] }}" :aria-valuenow="leader.km"
        aria-valuetext="Leader at {{ $leader['text'] }} of {{ Format::km($finishKm) }}" :aria-valuetext="`Leader at ${leader.text} of {{ Format::km($finishKm) }}`"
        class="relative h-1.5 w-full overflow-hidden rounded-full bg-white/10">
        <div class="h-full rounded-full bg-orange-500 transition-[width] duration-700" style="width: {{ $leader['percent'] }}%" :style="`width: ${leader.percent}%`"></div>
        @foreach ($checkpoints as $cp)
            @continue($cp['is_finish'])
            <span class="absolute top-0 h-full w-0.5 bg-[#0d0d0d]" style="left: {{ round($cp['km'] / $finishKm * 100, 1) }}%" aria-hidden="true"></span>
        @endforeach
    </div>

    {{-- Scrolls sideways on small screens. Pulled to the screen edge there so cards can scroll off it. --}}
    <div role="group" aria-label="Show arrivals at" class="-mx-4 mt-3 flex snap-x gap-3 overflow-x-auto px-4 pb-2 md:mx-0 md:px-0">
        <x-live.checkpoint-card code="" name="All checkpoints" :count="$counts['']" count-label="arrivals" :selected="$selected === null" />
        @foreach ($checkpoints as $c => $cp)
            <x-live.checkpoint-card :code="$cp['code']" :label="$cp['label']" :km="$cp['km']" :name="$cp['display']"
                :count="$counts[$cp['code']]" :selected="$selected === $cp['code']" />
        @endforeach
    </div>
</section>

@use('App\Support\LiveTracking\Format')
@php
    $raceSeconds = $race->stats()['race_seconds'];
    $gun = $race->course['gun'];
    $scheduled = $race->course['scheduled_gun'];
    $gunSet = $race->course['gun_set'];
    // Seconds since the gun by the server's clock, negative while it's still ahead. The page
    // counts on from here, so a viewer's device clock being off doesn't matter.
    $sinceGun = $gunSet ? (int) floor($gun->diffInSeconds($race->now)) : null;
@endphp

{{-- Race clock: real time, counted from the gun start set here (⋮ menu). No gun start means not started.
     Ticks every second, and flips from "Starts in …" to Running when a gun set ahead comes round. --}}
<div class="bg-white rounded-xl border border-gray-200 p-5" x-data="{
        sinceGun: @js($sinceGun),
        loadedAt: Date.now(),
        elapsed: @js($sinceGun),
        init() {
            if (this.sinceGun === null) return;
            setInterval(() => this.elapsed = this.sinceGun + Math.floor((Date.now() - this.loadedAt) / 1000), 1000);
        },
        get started() { return this.elapsed !== null && this.elapsed >= 0 },
        get clock() {
            const s = Math.max(0, this.elapsed ?? 0);
            return `${Math.floor(s / 3600)}:${String(Math.floor(s % 3600 / 60)).padStart(2, '0')}:${String(s % 60).padStart(2, '0')}`;
        },
        {{-- Same wording as Format::until(): in 5 min, in 23 h 5 min, in 24 h --}}
        get until() {
            const m = Math.ceil(-this.elapsed / 60), h = Math.floor(m / 60);
            return 'in ' + [h ? `${h} h` : '', m % 60 || !h ? `${m % 60} min` : ''].filter(Boolean).join(' ');
        },
    }">
    <div class="flex items-center justify-between gap-2">
        <h2 class="font-semibold text-gray-900">Race clock</h2>
        <div class="flex items-center gap-1">
            @if ($gunSet)
                {{-- A gun start in the future: easy to save by mistake, since the dialog starts on the scheduled date. --}}
                <span x-show="!started" @if ($race->started()) style="display: none" @endif
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border bg-amber-50 border-amber-200 text-amber-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Starts <span x-text="until">{{ Format::until($gun, $race->now) }}</span>
                </span>
                <span x-show="started" @unless ($race->started()) style="display: none" @endunless
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border bg-green-50 border-green-200 text-green-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Running
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border bg-gray-100 border-gray-200 text-gray-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Not started
                </span>
            @endif

            {{-- Kebab menu. A real button with aria-expanded; Escape or a click elsewhere closes it. --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                <button type="button" @click="open = !open" :aria-expanded="open" aria-expanded="false" aria-haspopup="menu"
                    aria-label="Race clock options"
                    class="p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition.opacity.duration.100ms role="menu"
                    class="absolute right-0 mt-1 w-52 rounded-lg border border-gray-200 bg-white shadow-lg py-1 z-20">
                    <button type="button" role="menuitem" @click="open = false; $dispatch('open-modal', 'set-gun')"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Set gun start…</button>
                    <div class="my-1 border-t border-gray-100" role="separator"></div>
                    <button type="button" role="menuitem" @click="open = false; $dispatch('open-modal', 'reset-race')"
                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Reset race…</button>
                </div>
            </div>
        </div>
    </div>

    <dl class="mt-4 space-y-3 text-sm">
        <div class="flex justify-between gap-4">
            <dt class="text-gray-500">Gun</dt>
            <dd class="text-right">
                @if ($gunSet)
                    <span class="font-mono text-gray-900">{{ Format::timeWithDay($gun) }}</span>
                @else
                    <span class="text-gray-900">Not set</span>
                @endif
                <span class="block text-xs text-gray-400">Scheduled {{ Format::timeWithDay($scheduled) }}</span>
            </dd>
        </div>
        <div class="flex justify-between gap-4">
            <dt class="text-gray-500">Race time</dt>
            <dd class="font-mono text-2xl font-bold text-gray-900 tabular-nums" x-text="clock">{{ Format::duration($raceSeconds, true) }}</dd>
        </div>
        <div class="flex justify-between gap-4">
            <dt class="text-gray-500">Runners</dt>
            <dd class="font-mono text-gray-900">{{ count($race->runners) }}</dd>
        </div>
    </dl>
    <p class="mt-4 text-xs text-gray-400">Runners are the approved registrations with a bib in this race.</p>
</div>

{{-- ========== Set gun start ========== --}}
{{-- Same look as x-confirm-modal, but with a form field. Reopens by itself if the time was rejected. --}}
<div x-data="{ open: @js($errors->has('gun_start')) }"
    x-init="$watch('open', value => value && $nextTick(() => $refs.gunInput.focus()))"
    x-on:open-modal.window="$event.detail === 'set-gun' && (open = true)">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="open = false" role="dialog" aria-modal="true" aria-labelledby="set-gun-title">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false"></div>

        <form method="POST" action="{{ route('admin.live.gun', $race->course['slug']) }}" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
            @csrf
            @method('PUT')
            <div class="p-6">
                <h3 id="set-gun-title" class="text-base font-bold text-gray-900">Set gun start · {{ $race->course['title'] }}</h3>
                <p class="text-sm text-gray-500 mt-1.5">
                    When the race started. Race time, splits and arrival windows all count from it, and until
                    it's set the race shows as not started.
                    Scheduled: <span class="font-mono">{{ Format::timeWithDay($scheduled) }}</span>.
                </p>
                <label for="gun-start" class="block text-xs font-medium text-gray-500 mt-4 mb-1.5">Gun went off at (race-day time, UTC+8)</label>
                <input id="gun-start" name="gun_start" type="datetime-local" x-ref="gunInput"
                    value="{{ old('gun_start', $gun->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-lg border border-gray-200 bg-white font-mono text-sm text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('gun_start')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex flex-wrap items-center justify-end gap-3">
                <button type="button" @click="open = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                {{-- For the moment itself: no typing, the server uses its own clock. --}}
                <button type="submit" name="now" value="1"
                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 rounded-lg transition-colors">Gun went off now</button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- ========== Reset race ========== --}}
<x-confirm-modal
    name="reset-race"
    :trigger="null"
    :action="route('admin.live.reset', $race->course['slug'])"
    method="DELETE"
    tone="red"
    :title="'Reset ' . $race->course['title'] . '?'"
    message="This can't be undone. Registrations and bibs are kept."
    confirm-label="Reset race">
    <ul class="mt-3 space-y-1 text-sm text-gray-700 list-disc pl-5">
        <li>Deletes {{ trans_choice(':count recorded checkpoint time|:count recorded checkpoint times', $resetCounts['times']) }}</li>
        <li>Clears {{ trans_choice(':count DNF|:count DNFs', $resetCounts['dnfs']) }}</li>
        <li>Clears the gun start, so the race shows as not started</li>
    </ul>
</x-confirm-modal>

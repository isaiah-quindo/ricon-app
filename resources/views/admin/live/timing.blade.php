@extends('layouts.admin')
@use('App\Support\LiveTracking\Format')
@section('title', 'Timing Console')

@section('content')

@include('admin.live._header', ['title' => 'Timing console'])

{{--
    For a volunteer at one checkpoint: type a bib, press Enter to record an In time.
    Each entry saves straight away (JSON), so the form is ready for the next runner.
--}}
<div x-data="timingConsole(@js([
        'cp'       => $checkpoints[$selected]['code'],
        'names'    => collect($checkpoints)->mapWithKeys(fn ($cp) => [$cp['code'] => "{$cp['label']} {$cp['display']}"]),
        'runners'  => $lookup,
        'recent'   => $recent,
        'prefix'   => $race->course['bib_prefix'],
        'tz'       => config('live.timezone'),
        'storeUrl' => route('admin.live.passes.store', $race->course['slug']),
        'undoUrl'  => route('admin.live.passes.destroy', [$race->course['slug'], '__ID__']),
    ]))"
    class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- Entry form --}}
    <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 p-6">
        <form @submit.prevent="record('in')" class="space-y-5">
            <div>
                <label for="checkpoint" class="block text-xs font-medium text-gray-500 mb-1.5">Checkpoint</label>
                <select id="checkpoint" x-model="cp" @change="checkpointChanged()"
                    class="w-full rounded-lg border border-gray-200 bg-white text-base text-gray-900 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @foreach ($checkpoints as $cp)
                        <option value="{{ $cp['code'] }}">{{ $cp['label'] }} · {{ $cp['display'] }} ({{ Format::km($cp['km']) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="bib" class="block text-xs font-medium text-gray-500 mb-1.5">Bib number</label>
                    {{-- "35" is enough; the race prefix is added automatically. --}}
                    <input id="bib" x-ref="bib" x-model="bib" type="text" inputmode="numeric" autocomplete="off" autofocus
                        placeholder="35" aria-describedby="bib-help"
                        class="w-full rounded-lg border border-gray-200 bg-white font-mono text-3xl text-gray-900 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label for="time" class="block text-xs font-medium text-gray-500 mb-1.5">Time</label>
                    {{-- Follows the clock until someone types a different time. --}}
                    <input id="time" x-model="time" @input="followClock = false" type="time" required
                        class="w-full rounded-lg border border-gray-200 bg-white font-mono text-xl text-gray-900 px-3 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <button type="button" x-show="!followClock" x-cloak @click="useClock()"
                        class="mt-1 text-xs font-medium text-indigo-600 hover:text-indigo-800">Use current time</button>
                </div>
            </div>

            {{-- What the typed bib matches, so a wrong number is caught before it's recorded. --}}
            <div id="bib-help" class="min-h-[3.5rem] rounded-lg border px-4 py-3 text-sm"
                :class="{
                    'border-gray-100 bg-gray-50 text-gray-500': !bib.trim(),
                    'border-green-200 bg-green-50 text-green-800': runner && !warning,
                    'border-amber-200 bg-amber-50 text-amber-800': warning,
                }">
                <template x-if="!bib.trim()"><p>Type a bib, then press Enter to record an <strong>In</strong> time.</p></template>
                <template x-if="bib.trim() && runner">
                    <div>
                        <p><strong class="font-semibold" x-text="runner.name"></strong> · <span class="font-mono" x-text="normalized"></span></p>
                        <p x-show="warning" x-text="warning"></p>
                    </div>
                </template>
                <template x-if="bib.trim() && !runner"><p x-text="warning"></p></template>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="submit" :disabled="saving"
                    class="px-4 py-3 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-60">
                    Record In <span class="font-normal text-indigo-200">(Enter)</span>
                </button>
                <button type="button" @click="record('out')" :disabled="saving"
                    class="px-4 py-3 bg-gray-900 text-white text-base font-semibold rounded-lg hover:bg-gray-800 transition-colors disabled:opacity-60">
                    Record Out
                </button>
            </div>

            <p class="text-sm" role="status" aria-live="polite">
                <span x-show="message" x-text="message" :class="failed ? 'text-red-700' : 'text-green-700'"></span>
            </p>
        </form>
    </div>

    {{-- Recent entries at this checkpoint --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Recent entries</h2>
            <p class="text-xs text-gray-500" x-text="names[cp]"></p>
        </div>
        <ul class="divide-y divide-gray-100">
            <template x-for="(entry, i) in (entries[cp] ?? [])" :key="entry.id + entry.type">
                <li class="px-5 py-3 flex items-center gap-3" :class="entry.fresh && 'bg-indigo-50'">
                    <span class="font-mono text-sm text-gray-900 w-20" x-text="entry.at"></span>
                    <span class="px-2 py-0.5 rounded text-xs font-semibold"
                        :class="entry.type === 'In' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-200 text-gray-700'"
                        x-text="entry.type"></span>
                    <span class="min-w-0 flex-1 truncate text-sm">
                        <span class="font-mono text-gray-500" x-text="entry.bib"></span>
                        <span class="text-gray-900" x-text="entry.name"></span>
                    </span>
                    <button type="button" @click="undo(i)" :disabled="saving" class="text-sm font-medium text-red-600 hover:text-red-800">
                        Undo<span class="sr-only" x-text="' ' + entry.type + ' for ' + entry.name"></span>
                    </button>
                </li>
            </template>
            <li x-show="!(entries[cp] ?? []).length" class="px-5 py-8 text-center text-sm text-gray-500">No entries at this checkpoint yet.</li>
        </ul>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('timingConsole', ({ cp, names, runners, recent, prefix, tz, storeUrl, undoUrl }) => ({
        cp,
        names,
        bib: '',
        time: '',           // "HH:MM" in race-day time
        followClock: true,  // keep the time field on the current time until someone edits it
        entries: recent,    // { checkpointCode: [{ id, bib, name, type, at }] }, newest first
        message: '',
        failed: false,
        saving: false,

        init() {
            this.useClock();
            setInterval(() => this.followClock && (this.time = this.clockTime()), 15000);
        },

        // "35" or "035" becomes "100-035".
        get normalized() {
            const typed = this.bib.trim();
            return /^\d+$/.test(typed) ? `${prefix}-${typed.padStart(3, '0')}` : typed;
        },

        get runner() {
            return runners[this.normalized] ?? null;
        },

        // Things a volunteer should double-check before recording. Not blocking.
        get warning() {
            if (!this.bib.trim()) return null;
            if (!this.runner) return `No runner with bib ${this.normalized} in this race.`;
            if (this.runner.status === 'dnf') return 'This runner is marked DNF.';
            const pass = this.runner.passes[this.cp];
            if (pass?.in) return `Already has an In time here (${pass.in}). Recording again replaces it.`;
            return null;
        },

        async record(type) {
            if (!this.runner) {
                this.$refs.bib.focus();
                return;
            }

            const result = await this.send(storeUrl, 'POST', { bib: this.normalized, checkpoint: this.cp, type, time: this.time });
            if (!result) return;

            this.entries[this.cp] = [{ ...result, fresh: true }, ...(this.entries[this.cp] ?? [])];
            this.runner.passes[this.cp] = result.passes[this.cp];
            this.say(`${result.type} recorded: ${result.name} at ${result.at}.`);

            // Ready for the next runner straight away.
            this.bib = '';
            if (this.followClock) this.time = this.clockTime();
            this.$refs.bib.focus();
        },

        async undo(index) {
            const entry = this.entries[this.cp][index];
            const type = entry.type.toLowerCase();
            const result = await this.send(`${undoUrl.replace('__ID__', entry.id)}?type=${type}`, 'DELETE');
            if (!result) return;

            this.entries[this.cp].splice(index, 1);
            const pass = runners[entry.bib]?.passes[this.cp];
            if (pass) pass[type] = null;
            this.say(`Removed ${entry.type} for ${entry.name}.`);
        },

        // Sends JSON with Laravel's CSRF token. Shows the error and returns null if it fails.
        async send(url, method, body = null) {
            this.saving = true;
            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: body ? JSON.stringify(body) : null,
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    this.say(data.message ?? 'Not saved. Check the connection and try again.', true);
                    return null;
                }
                return data;
            } catch {
                this.say('Not saved. Check the connection and try again.', true);
                return null;
            } finally {
                this.saving = false;
            }
        },

        say(text, failed = false) {
            this.message = text;
            this.failed = failed;
        },

        checkpointChanged() {
            this.message = '';
            const url = new URL(window.location);
            url.searchParams.set('cp', this.cp);
            history.replaceState(null, '', url);
            this.$refs.bib.focus();
        },

        useClock() {
            this.followClock = true;
            this.time = this.clockTime();
        },

        // Current race-day time as "HH:MM", whatever timezone this device is set to.
        clockTime() {
            return new Intl.DateTimeFormat('en-GB', { timeZone: tz, hour: '2-digit', minute: '2-digit', hourCycle: 'h23' })
                .format(new Date());
        },
    }));
});
</script>
@endpush

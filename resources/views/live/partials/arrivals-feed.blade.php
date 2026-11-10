<section aria-labelledby="feed-heading">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 id="feed-heading" class="text-xl font-bold text-white">Latest arrivals</h2>
            <p class="mt-1 text-sm text-gray-400"><span x-text="cpName">All checkpoints</span>, newest first</p>
        </div>
        <div class="w-full sm:w-72">
            <label for="runner-search" class="mb-1 block text-xs font-medium text-gray-300">Find a runner</label>
            {{-- Searched on the server, a moment after typing stops. --}}
            <input id="runner-search" type="search" x-model="query" @input.debounce.300ms="search()"
                placeholder="Name or bib number" autocomplete="off"
                class="min-h-[44px] w-full rounded-lg border-white/15 bg-[#1a1a1a] text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500">
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#111111]">
        {{-- The table keeps its columns on phones and scrolls sideways inside this box instead. --}}
        <div class="overflow-x-auto transition-opacity" x-show="matches > 0" :class="loading && 'opacity-60'">
            <table class="w-full min-w-[720px] text-left text-sm">
                <caption class="sr-only">Checkpoint arrivals, newest first</caption>
                <thead class="border-b border-white/10 text-xs uppercase tracking-wider text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold">Arrived</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Runner</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Checkpoint</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Position</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody x-ref="feed" class="divide-y divide-white/5">
                    @include('live.partials.feed-rows')
                </tbody>
            </table>
        </div>

        <div x-show="matches > limit" x-cloak class="border-t border-white/10 p-3 text-center">
            <button type="button" @click="showMore()" :disabled="loading"
                class="min-h-[44px] rounded-lg px-4 text-sm font-semibold text-orange-400 hover:bg-white/5 hover:text-orange-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">
                Show more arrivals
            </button>
        </div>

        {{-- Empty states: nothing matches the search, or nobody has reached this checkpoint yet. --}}
        <div x-show="matches === 0" x-cloak class="px-6 py-14 text-center" role="status">
            <template x-if="query.trim()">
                <p class="text-gray-300">No runners match "<span x-text="query.trim()"></span>" at <span x-text="cpName"></span>.</p>
            </template>
            <template x-if="!query.trim()">
                <div>
                    <p class="font-semibold text-white" x-text="cp ? `No one has reached ${cpName} yet.` : 'No arrivals yet.'">No arrivals yet.</p>
                    <p class="mt-2 text-sm text-gray-400" x-show="expectedText">
                        Leaders expected <span class="font-mono text-gray-200" x-text="expectedText"></span>
                    </p>
                </div>
            </template>
        </div>
    </div>
</section>

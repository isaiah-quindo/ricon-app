{{--
    Spec fallback: the spec asks for a dark card here, but the whole page is already dark,
    so this panel stands out with a lighter surface and an orange top edge instead.
    Rendered by Alpine from the viewer's followed bibs (see follow-store).
--}}
<section aria-labelledby="following-heading" class="rounded-2xl border border-white/10 border-t-2 border-t-orange-500 bg-[#1a1a1a] p-5">
    <h2 id="following-heading" class="text-lg font-bold text-white">Following</h2>

    <p x-show="followed.length === 0" class="mt-2 text-sm text-gray-400">
        Open a runner's page and choose Follow to keep them here.
    </p>

    <ul x-show="followed.length > 0" x-cloak class="mt-2 divide-y divide-white/10">
        <template x-for="runner in followed" :key="runner.bib">
            <li class="py-3">
                <a :href="runner.url" class="flex min-h-[44px] flex-wrap items-center gap-x-2 rounded-md font-semibold text-white hover:text-orange-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">
                    <span x-text="runner.name"></span>
                    <span class="rounded-md border border-white/15 bg-white/5 px-2 py-0.5 font-mono text-xs font-medium text-gray-200" x-text="runner.bib"></span>
                </a>
                <p class="text-sm text-gray-300">
                    <span x-text="runner.last"></span>
                    <span class="font-mono text-gray-400" x-show="runner.lastTime" x-text="'· ' + runner.lastTime"></span>
                </p>
                <p class="mt-1 text-sm" x-show="runner.next">
                    <span class="text-gray-400">Next:</span>
                    <span class="text-gray-200" x-text="runner.next"></span>
                    <span class="block font-mono text-orange-300" x-show="runner.eta" x-text="runner.eta"></span>
                    <span class="block text-gray-400" x-show="!runner.eta">Estimate after 2 checkpoints</span>
                </p>
                <p class="mt-1 text-sm text-red-300" x-show="runner.status === 'dnf'">Did not finish</p>
                <p class="mt-1 text-sm text-emerald-300" x-show="runner.status === 'finished'">Finished</p>
            </li>
        </template>
    </ul>
</section>

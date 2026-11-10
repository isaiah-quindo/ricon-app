{{--
    "Updated 12 seconds ago", from the liveUpdates helper (see live-updates-script). If an
    update fails it says so, and keeps retrying on the normal schedule.
--}}
<p class="mt-2 text-sm text-gray-400">
    <span x-show="!failed">Updated <span x-text="updatedText()">just now</span></span>
    <span x-show="failed" x-cloak class="text-amber-300">Couldn't update. Retrying…</span>
</p>

{{-- Bib and current status on the runner page. Re-rendered by each update. --}}
<div class="flex flex-wrap items-center gap-2">
    <x-live.bib-chip :bib="$runner['bib']" class="text-sm" />
    <x-live.status-pill :state="$runner['status']" :label="$race->statusText($runner)" />
</div>

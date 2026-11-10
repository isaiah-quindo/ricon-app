@props(['bib'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md border border-white/15 bg-white/5 px-2 py-0.5 font-mono text-xs font-medium text-gray-200']) }}>
    <span class="sr-only">Bib </span>{{ $bib }}
</span>

@props(['label', 'value', 'mono' => false])

{{-- Meant to sit inside a <dl>, so screen readers pair each label with its value. --}}
<div class="rounded-2xl border border-white/10 bg-[#1a1a1a] px-4 py-3">
    <dt class="text-xs uppercase tracking-wider text-gray-400">{{ $label }}</dt>
    <dd class="mt-1 text-2xl font-black text-white {{ $mono ? 'font-mono tabular-nums' : "font-['Kufam']" }}"
        {{ $attributes }}>{{ $value }}</dd>
</div>

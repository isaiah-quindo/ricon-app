<div class="lg:sticky lg:top-24" x-data="{ view: @js(array_key_first($product['images'])) }">
    <div class="aspect-square bg-[#c9c9c9] rounded-2xl overflow-hidden p-8 flex items-center justify-center">
        @foreach($product['images'] as $view => $src)
        <img src="{{ $src }}" alt="{{ $product['name'] }}, {{ strtolower($view) }} view" loading="lazy"
            x-show="view === @js($view)" @if(!$loop->first) style="display: none;" @endif
            class="w-full h-full object-contain">
        @endforeach
    </div>
    @if(count($product['images']) > 1)
    <div class="flex gap-2 mt-3">
        @foreach(array_keys($product['images']) as $view)
        <button type="button" @click="view = @js($view)"
            :class="view === @js($view) ? 'bg-orange-600 border-orange-600 text-white' : 'bg-[#111111] border-white/10 text-gray-400 hover:text-white'"
            class="flex-1 py-2.5 rounded-lg border text-sm font-bold transition-colors">
            {{ $view }}
        </button>
        @endforeach
    </div>
    @endif
</div>

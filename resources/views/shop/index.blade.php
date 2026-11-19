@extends('layouts.public')
@section('title', 'Shop')
@section('og_title', 'TGC100 Merch · The Great Cordillera 100 Ultra Trail')
@section('og_description', 'The official TGC100 collection. Trail cap and the Running in Constant Tee, carrying the trail patterns of the Cordillera. Ships nationwide via LBC Express.')

@section('content')
{{-- ======================================================== --}}
{{-- HERO --}}
{{-- ======================================================== --}}
<section class="relative overflow-hidden border-b border-white/10 pt-36 bg-[#0a0a0a]">
    <canvas id="shop-topo" class="absolute inset-0 w-full h-full" aria-hidden="true"></canvas>
    <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-[#0a0a0a]/20 via-[#0a0a0a]/80 to-[#0a0a0a]"></div>
    {{-- Orange glow over the ridgelines --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
        style="background:
            radial-gradient(ellipse 60% 70% at 15% 0%, rgba(249,115,22,0.28), rgba(249,115,22,0) 70%),
            radial-gradient(ellipse 50% 60% at 90% 10%, rgba(234,88,12,0.14), rgba(234,88,12,0) 70%);"></div>
    <div class="relative z-10 mx-auto px-6 md:px-8" style="max-width:1280px;">
        <span class="inline-flex items-center bg-orange-500/10 border border-orange-500 text-orange-500 font-bold text-xs uppercase tracking-wider px-3.5 py-1.5 rounded-full mb-5">Now Available</span>
        <h1 class="text-5xl md:text-6xl font-black text-white leading-none mb-4">
            TGC100 <span class="text-orange-500">Merch</span>
        </h1>
        <div class="w-48 h-[3px] bg-orange-500 rounded-full mb-6"></div>
        <p class="text-lg text-gray-300 max-w-2xl mb-3">The official TGC100 collection, trail cap and the Running in Constant Tee, carrying the trail patterns of the Cordillera with you.</p>
        <p class="text-sm text-gray-400 max-w-2xl">Order now and we'll ship nationwide via LBC Express.</p>
        <div class="flex flex-wrap gap-3 mt-8 pb-14">
            @if($products->isNotEmpty())
            <a href="#{{ $products->first()->slug }}" class="py-3 px-5 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                Shop Now
            </a>
            @endif
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- PRODUCTS --}}
{{-- ======================================================== --}}
@forelse($products as $product)
@php
$config = [
    'colors' => $product->colors->map(fn($c) => [
        'id'    => $c->id,
        'label' => $c->label,
        'hex'   => $c->hex,
        'front' => $c->front_url,
        'back'  => $c->back_url,
    ])->values(),
    'sizes' => $product->sizes,
    // colorId => { size (or "_" for one size) => variantId }
    'variants' => $product->variants->groupBy('product_color_id')
        ->map(fn($vs) => $vs->mapWithKeys(fn($v) => [$v->size ?? '_' => $v->id])),
];
@endphp
<section id="{{ $product->slug }}" class="{{ $loop->odd ? 'bg-[#0a0a0a]' : 'bg-[#0d0d0d]' }} border-t border-white/10 py-20 scroll-mt-16">
    <div class="mx-auto px-6 md:px-8" style="max-width:1280px;" x-data="shopProduct(@js($config))">
        @if($product->eyebrow)
        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-4">{{ $product->eyebrow }}</p>
        @endif
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ $product->name }}</h2>
        @if($product->intro)
        <p class="text-gray-400 max-w-2xl mb-10">{{ $product->intro }}</p>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-start">

            {{-- Gallery --}}
            <div class="lg:sticky lg:top-24">
                <div class="relative aspect-square bg-[#c9c9c9] rounded-2xl overflow-hidden p-8 flex items-center justify-center">
                    <img :src="image()" alt="{{ $product->name }}" x-show="image()"
                        :class="colorSoldOut(colorId) && 'opacity-40 grayscale-[40%]'"
                        class="w-full h-full object-contain transition-opacity">
                    <span x-show="colorSoldOut(colorId)" style="display: none;"
                        class="absolute font-black text-2xl tracking-wider text-white bg-black/60 px-5 py-2 rounded-lg">SOLD OUT</span>
                </div>
                <div class="flex gap-2 mt-3" x-show="color().back" style="display: none;">
                    <template x-for="v in ['front', 'back']" :key="v">
                        <button type="button" @click="view = v"
                            :class="view === v ? 'bg-orange-600 border-orange-600 text-white' : 'bg-[#111111] border-white/10 text-gray-400 hover:text-white'"
                            class="flex-1 py-2.5 rounded-lg border text-sm font-bold capitalize transition-colors" x-text="v"></button>
                    </template>
                </div>
            </div>

            {{-- Details --}}
            <div>
                <h3 class="text-2xl md:text-3xl font-black text-white mb-2">{{ $product->name }}</h3>
                <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-5">
                    <span class="text-orange-500 font-extrabold text-xl">&#8369;{{ number_format($product->price) }}</span>
                    @if($product->compare_at_price)
                    <span class="text-gray-500 font-semibold line-through">&#8369;{{ number_format($product->compare_at_price) }}</span>
                    @endif
                    @if($product->usd_price)
                    <span class="text-gray-400 text-sm font-bold">(~${{ $product->usd_price }} USD)</span>
                    @endif
                    <p class="w-full text-gray-500 text-xs">In stock, ships via LBC Express</p>
                </div>

                @if($product->description)
                <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-xl">{{ $product->description }}</p>
                @endif

                {{-- Color --}}
                <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-4">Color</p>
                <div class="flex flex-wrap gap-2 mb-6">
                    {{-- Fixed width per swatch so circles are evenly spaced regardless of label length --}}
                    <template x-for="c in colors" :key="c.id">
                        <button type="button" @click="pickColor(c.id)" class="w-20 flex flex-col items-center gap-1.5">
                            <span class="w-8 h-8 shrink-0 rounded-full border-2 border-gray-700 transition-shadow"
                                :style="'background:' + c.hex + ';' + (colorId === c.id ? 'box-shadow: 0 0 0 2px #0a0a0a, 0 0 0 4px #f97316;' : '')"></span>
                            <span class="text-xs font-semibold text-center leading-tight" :class="colorId === c.id ? 'text-white' : 'text-gray-400'" x-text="c.label"></span>
                            <span x-show="colorSoldOut(c.id)" class="text-[10px] font-bold uppercase text-red-400 -mt-1">Sold out</span>
                        </button>
                    </template>
                </div>

                @if($product->specs)
                <ul class="space-y-2.5 mb-7">
                    @foreach($product->specs as $key => $value)
                    <li class="flex items-start gap-2.5 text-sm text-gray-400">
                        <svg class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span><span class="text-white font-semibold">{{ $key }}:</span> {{ $value }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="bg-[#161616] border-l-[3px] border-orange-500 rounded-r-lg px-5 py-4 mb-7">
                    <p class="text-gray-400 text-sm"><span class="text-white font-semibold">In stock now.</span> Add to your cart, then pay in full via bank transfer at checkout. We ship via LBC Express once your payment is confirmed.</p>
                </div>

                {{-- Size --}}
                @if($product->hasSizes())
                <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-4">Size</p>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                    @foreach($product->sizes as $size)
                    <button type="button" @click="pickSize(@js($size))" :disabled="stockOf(colorId, @js($size)) === 0"
                        :class="size === @js($size) ? 'bg-orange-500/10 border-orange-500 text-white' : 'bg-[#1f2937] border-gray-700 text-gray-400 hover:border-gray-500'"
                        class="py-3 rounded-lg border text-sm font-bold transition-colors disabled:opacity-30 disabled:line-through disabled:pointer-events-none">
                        {{ $size }}
                    </button>
                    @endforeach
                </div>
                @endif

                <p x-show="lowStock()" x-text="lowStock()" class="text-amber-400 text-xs mt-3" style="display: none;"></p>
                <p x-show="error" x-text="error" class="text-orange-500 text-sm mt-3" style="display: none;"></p>

                <div class="flex flex-wrap items-center gap-3 mt-5">
                    <div class="inline-flex items-center border border-gray-700 rounded-lg overflow-hidden">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" :disabled="qty <= 1"
                            class="w-10 h-11 bg-[#1f2937] text-white hover:bg-[#161616] disabled:opacity-40" aria-label="Decrease quantity">&minus;</button>
                        <span class="w-10 text-center text-white font-bold tabular-nums" x-text="qty">1</span>
                        <button type="button" @click="qty = Math.min(maxQty(), qty + 1)" :disabled="qty >= maxQty()"
                            class="w-10 h-11 bg-[#1f2937] text-white hover:bg-[#161616] disabled:opacity-40" aria-label="Increase quantity">+</button>
                    </div>
                    <button type="button" @click="add()" :disabled="colorSoldOut(colorId)"
                        class="py-3 px-6 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors disabled:opacity-40 disabled:pointer-events-none">
                        <span x-text="colorSoldOut(colorId) ? 'Sold Out' : 'Add to Cart'">Add to Cart</span>
                    </button>
                    <span x-show="justAdded" x-transition.opacity style="display: none;" class="text-green-400 text-sm font-semibold">&check; Added</span>
                </div>
            </div>
        </div>
    </div>
</section>
@empty
<section class="bg-[#0a0a0a] border-t border-white/10 py-24">
    <div class="mx-auto px-6 md:px-8 text-center" style="max-width:1280px;">
        <h2 class="text-2xl font-bold text-white mb-2">New merch is on the way</h2>
        <p class="text-gray-400">Check back soon.</p>
    </div>
</section>
@endforelse

@include('shop._cart_drawer')
@endsection

@push('scripts')
@include('shop._cart_store')
<script>
    function shopProduct(config) {
        return {
            ...config,
            colorId: config.colors[0]?.id,
            view: 'front',
            size: null,
            qty: 1,
            error: '',
            justAdded: false,

            init() {
                // Open on the first color that still has stock
                const inStock = this.colors.find(c => !this.colorSoldOut(c.id));
                if (inStock) this.colorId = inStock.id;
            },

            color() {
                return this.colors.find(c => c.id === this.colorId) || {};
            },

            image() {
                const c = this.color();
                return this.view === 'back' && c.back ? c.back : c.front;
            },

            variantFor(colorId, size) {
                return this.variants[colorId]?.[size ?? '_'];
            },

            stockOf(colorId, size) {
                return this.$store.cart.catalog[this.variantFor(colorId, size)]?.stock ?? 0;
            },

            colorSoldOut(colorId) {
                return (this.sizes || [null]).every(s => this.stockOf(colorId, s) === 0);
            },

            selected() {
                if (this.sizes && !this.size) return null;
                return this.variantFor(this.colorId, this.size);
            },

            maxQty() {
                const id = this.selected();
                return id ? Math.max(1, this.$store.cart.available(id)) : this.$store.cart.maxQty;
            },

            lowStock() {
                const id = this.selected();
                const stock = id ? this.$store.cart.catalog[id]?.stock : 0;
                return stock > 0 && stock <= 5 ? 'Only ' + stock + ' left in this option.' : '';
            },

            pickColor(id) {
                this.colorId = id;
                if (!this.color().back) this.view = 'front';
                if (this.size && this.stockOf(id, this.size) === 0) this.size = null;
                this.qty = Math.min(this.qty, this.maxQty());
                this.error = '';
            },

            pickSize(size) {
                this.size = size;
                this.qty = Math.min(this.qty, this.maxQty());
                this.error = '';
            },

            add() {
                if (this.sizes && !this.size) {
                    this.error = 'Select a size before adding to cart.';
                    return;
                }
                const id = this.selected();
                if (!id || this.stockOf(this.colorId, this.size) === 0) {
                    this.error = 'This option is sold out.';
                    return;
                }
                if (this.$store.cart.available(id) === 0) {
                    this.error = 'You already have all the remaining stock of this option in your cart.';
                    return;
                }
                const added = this.$store.cart.add(id, this.qty);
                this.error = added < this.qty ? 'Only ' + added + ' more could be added, that is all we have left.' : '';
                this.qty = 1;
                this.justAdded = true;
                setTimeout(() => this.justAdded = false, 2000);
            },
        };
    }

    // Layered ridgelines behind the hero
    (function () {
        var canvas = document.getElementById('shop-topo');
        if (!canvas) return;
        function draw() {
            var rect = canvas.parentElement.getBoundingClientRect();
            var dpr = window.devicePixelRatio || 1;
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            var ctx = canvas.getContext('2d');
            ctx.scale(dpr, dpr);
            ctx.clearRect(0, 0, rect.width, rect.height);
            for (var i = 0; i < 4; i++) {
                var baseY = rect.height * (0.4 + i * 0.14), amp = 30 + i * 14, freq = 0.0016 + i * 0.0009, phase = i * 3.1;
                ctx.beginPath();
                ctx.moveTo(0, rect.height);
                for (var x = 0; x <= rect.width; x += 8) {
                    ctx.lineTo(x, baseY - Math.abs(Math.sin(x * freq + phase)) * amp - Math.abs(Math.sin(x * freq * 2.7 + phase)) * (amp * 0.4));
                }
                ctx.lineTo(rect.width, rect.height);
                ctx.closePath();
                ctx.fillStyle = 'rgba(30,22,16,' + (0.5 - (i / 4) * 0.08) + ')';
                ctx.fill();
            }
        }
        draw();
        window.addEventListener('resize', draw);
    })();
</script>
@endpush

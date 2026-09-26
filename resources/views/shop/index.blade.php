@extends('layouts.public')
@section('title', 'Shop')
@section('og_title', 'TGC100 Merch — The Great Cordillera 100 Ultra Trail')
@section('og_description', 'The official TGC100 collection. Finisher hoodie, trail cap, and tote carrying the trail patterns of the Cordillera. Pre-order now.')

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
    <div class="relative z-10 mx-auto px-8" style="max-width:1280px;">
        <span class="inline-flex items-center bg-orange-500/10 border border-orange-500 text-orange-500 font-bold text-xs uppercase tracking-wider px-3.5 py-1.5 rounded-full mb-5">You Get the First Look</span>
        <h1 class="text-5xl md:text-6xl font-black text-white leading-none mb-4">
            TGC100 <span class="text-orange-500">Merch</span>
        </h1>
        <div class="w-48 h-[3px] bg-orange-500 rounded-full mb-6"></div>
        <p class="text-lg text-gray-300 max-w-2xl mb-3">Before anyone else sees it, you do. The official TGC100 collection, hoodie, trail cap, and tote, carrying the trail patterns of the Cordillera with you.</p>
        <p class="text-sm text-gray-400 max-w-2xl">Pre-order pricing runs {{ config('shop.window') }}. Reserve yours now, no payment needed today.</p>
        <div class="flex flex-wrap gap-3 mt-8 pb-14">
            <a href="#{{ array_key_first($products) }}" class="py-3 px-5 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                Reserve Yours
            </a>
            @foreach($products as $slug => $product)
            <a href="#{{ $slug }}" class="py-3 px-5 inline-flex items-center text-sm font-bold rounded-lg border border-white/20 text-white hover:border-white/50 transition-colors">
                {{ $product['short_name'] }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- PRODUCTS --}}
{{-- ======================================================== --}}
@foreach($products as $slug => $product)
@php $product = ['slug' => $slug] + $product; @endphp
<section id="{{ $slug }}" class="{{ $loop->odd ? 'bg-[#0a0a0a]' : 'bg-[#0d0d0d]' }} border-t border-white/10 py-20 scroll-mt-16">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ $product['heading'] }}</h2>
        <p class="text-gray-400 max-w-2xl mb-10">{{ $product['intro'] }}</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-start">
            @include('shop._gallery', ['product' => $product])
            @include('shop._details', ['product' => $product])
        </div>
    </div>
</section>
@endforeach
@endsection

@push('scripts')
@include('shop._script')
<script>
    // Layered ridgelines behind the hero, same as the merch mockup
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

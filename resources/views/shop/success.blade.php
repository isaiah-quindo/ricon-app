@extends('layouts.public')
@section('title', 'Order Received · Shop')

@section('content')
<section class="bg-[#0a0a0a] pt-32 pb-20">
    <div class="mx-auto px-6 md:px-8" style="max-width:640px;">
        <div class="bg-[#111111] border border-white/10 rounded-2xl p-8 md:p-10 text-center">
            <div class="w-12 h-12 rounded-full bg-orange-500/10 text-orange-500 flex items-center justify-center mx-auto mb-5 text-xl font-extrabold">&check;</div>
            <h1 class="text-2xl font-extrabold text-white mb-3">Order Received</h1>
            <p class="text-gray-400 text-sm max-w-md mx-auto mb-8">
                Thanks for your payment. We'll verify your proof of payment and confirm your order by email at
                <span class="text-white font-semibold">{{ $order->email }}</span>.
                @if($order->isPickup())
                We'll also send your pickup details.
                @else
                Once it ships, we'll email your LBC tracking number.
                @endif
            </p>

            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 mb-6">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Order Reference</p>
                <p class="text-orange-500 font-extrabold text-2xl tracking-wide">{{ $order->reference }}</p>
                <p class="text-gray-500 text-xs mt-1">Keep this handy if you need to ask about your order.</p>
            </div>

            <dl class="text-left text-sm space-y-2 mb-8">
                @foreach($order->items as $item)
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-300">{{ $item->quantity }} &times; {{ $item->product_name }} <span class="text-gray-500">&middot; {{ $item->options_label }}</span></dt>
                    <dd class="text-white font-semibold whitespace-nowrap">&#8369;{{ number_format($item->line_total, 2) }}</dd>
                </div>
                @endforeach
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-400">Shipping &middot; {{ $order->shipping_area_label }}</dt>
                    <dd class="text-white font-semibold whitespace-nowrap">&#8369;{{ number_format($order->shipping_fee, 2) }}</dd>
                </div>
                <div class="flex justify-between gap-4 pt-2 border-t border-white/10">
                    <dt class="text-white font-bold">Total Paid</dt>
                    <dd class="text-orange-500 font-extrabold whitespace-nowrap">&#8369;{{ number_format($order->total, 2) }}</dd>
                </div>
            </dl>

            <a href="{{ route('shop.index') }}" class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-bold rounded-lg border border-white/20 text-white hover:border-orange-500 hover:text-orange-500 transition-colors">
                Back to the Shop
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // The order is in; empty the browser cart
    try { localStorage.removeItem('ricon_shop_cart_v1'); } catch (e) {}
</script>
@endpush

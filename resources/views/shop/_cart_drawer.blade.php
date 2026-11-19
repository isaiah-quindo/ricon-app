{{-- Floating cart button --}}
<button type="button" x-data x-show="$store.cart.count() > 0" x-transition style="display: none;"
    @click="$store.cart.open = true"
    class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2.5 rounded-full bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm pl-4 pr-5 py-3 shadow-lg shadow-black/40 transition-colors"
    aria-label="Open cart">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
    </svg>
    <span>Cart</span>
    <span class="inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full bg-white text-orange-700 text-xs" x-text="$store.cart.count()"></span>
</button>

{{-- Drawer --}}
<div x-data x-show="$store.cart.open" style="display: none;" class="fixed inset-0 z-[60]"
    @keydown.escape.window="$store.cart.open = false" role="dialog" aria-modal="true" aria-label="Your cart">
    <div x-show="$store.cart.open" x-transition.opacity class="absolute inset-0 bg-black/70" @click="$store.cart.open = false"></div>

    <div x-show="$store.cart.open"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="absolute right-0 top-0 h-full w-full max-w-md bg-[#111111] border-l border-white/10 flex flex-col">

        <div class="flex items-center justify-between px-6 h-16 border-b border-white/10 flex-shrink-0">
            <h2 class="text-lg font-bold text-white">Your Cart <span class="text-gray-500 font-medium" x-text="'(' + $store.cart.count() + ')'"></span></h2>
            <button type="button" @click="$store.cart.open = false" class="text-gray-400 hover:text-white p-1" aria-label="Close cart">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-5">
            <p x-show="$store.cart.notice" x-text="$store.cart.notice" class="text-amber-400 text-xs mb-4"></p>

            <div x-show="$store.cart.count() === 0" class="text-center text-gray-500 text-sm border border-dashed border-white/15 rounded-lg p-6">
                Your cart is empty.
            </div>

            <ul class="space-y-3">
                <template x-for="line in $store.cart.lines()" :key="line.id">
                    <li class="flex gap-3 bg-[#0a0a0a] border border-white/10 rounded-xl p-3">
                        <div class="w-16 h-16 rounded-lg bg-[#c9c9c9] flex-shrink-0 overflow-hidden p-1">
                            <img :src="line.image" :alt="line.name" class="w-full h-full object-contain" x-show="line.image">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-white text-sm font-bold truncate" x-text="line.name"></p>
                                    <p class="text-gray-500 text-xs" x-text="$store.cart.options(line)"></p>
                                </div>
                                <button type="button" @click="$store.cart.remove(line.id)" class="text-gray-500 hover:text-orange-500 text-lg leading-none p-1" aria-label="Remove item">&times;</button>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <div class="inline-flex items-center border border-white/10 rounded-lg">
                                    <button type="button" @click="$store.cart.setQty(line.id, line.qty - 1)" :disabled="line.qty <= 1"
                                        class="w-8 h-8 text-gray-300 hover:text-white disabled:opacity-30" aria-label="Decrease quantity">&minus;</button>
                                    <span class="w-7 text-center text-white text-sm font-bold tabular-nums" x-text="line.qty"></span>
                                    <button type="button" @click="$store.cart.setQty(line.id, line.qty + 1)" :disabled="line.qty >= $store.cart.limit(line.id)"
                                        class="w-8 h-8 text-gray-300 hover:text-white disabled:opacity-30" aria-label="Increase quantity">+</button>
                                </div>
                                <span class="text-orange-500 text-sm font-bold" x-text="$store.cart.peso(line.total)"></span>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>

        <div class="border-t border-white/10 px-6 py-5 flex-shrink-0">
            <div class="flex items-center justify-between mb-1">
                <span class="text-gray-400 text-sm">Subtotal</span>
                <span class="text-white font-extrabold text-lg" x-text="$store.cart.peso($store.cart.subtotal())"></span>
            </div>
            <p class="text-gray-500 text-xs mb-4">Shipping is calculated at checkout.</p>
            <a href="{{ route('shop.checkout') }}"
                :class="$store.cart.count() === 0 && 'opacity-40 pointer-events-none'"
                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                Checkout &rarr;
            </a>
            <button type="button" @click="$store.cart.open = false"
                class="mt-2 w-full py-3 px-4 text-sm font-bold rounded-lg border border-white/15 text-white hover:border-white/40 transition-colors">
                Continue Shopping
            </button>
        </div>
    </div>
</div>

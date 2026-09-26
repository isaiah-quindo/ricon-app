@php
$input = 'w-full bg-[#0a0a0a] border border-white/10 rounded-lg text-white text-sm px-4 py-3 placeholder-gray-600 focus:border-orange-500 focus:ring-0 focus:outline-none transition-colors';
$label = 'block text-sm font-medium text-gray-300 mb-1.5';
$id = fn(string $field) => $product['slug'] . '-' . $field;
@endphp

<div x-data="shopOrder(@js([
    'name'     => $product['short_name'],
    'price'    => $product['price'],
    'hasSizes' => (bool) $product['sizes'],
    'maxQty'   => config('shop.max_quantity'),
    'orderUrl' => route('shop.order', $product['slug']),
    'idPrefix' => $product['slug'],
]))">
    <h3 class="text-2xl md:text-3xl font-black text-white mb-2">{{ $product['name'] }}</h3>
    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-5">
        <span class="text-orange-500 font-extrabold text-xl">&#8369;{{ number_format($product['price']) }}</span>
        <span class="text-gray-500 font-semibold line-through">&#8369;{{ number_format($product['onsite_price']) }}</span>
        <span class="text-gray-400 text-sm font-bold">(~${{ $product['usd'] }} USD)</span>
        <p class="w-full text-gray-500 text-xs">Pre-order price, {{ config('shop.window') }} &middot; USD price approximate, based on exchange rate at time of order</p>
    </div>

    <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-xl">{{ $product['description'] }}</p>

    <ul class="space-y-2.5 mb-7">
        @foreach($product['specs'] as $key => $value)
        <li class="flex items-start gap-2.5 text-sm text-gray-400">
            <svg class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span><span class="text-white font-semibold">{{ $key }}:</span> {{ $value }}</span>
        </li>
        @endforeach
    </ul>

    <div class="bg-[#161616] border-l-[3px] border-orange-500 rounded-r-lg px-5 py-4 mb-7">
        <p class="text-gray-400 text-sm"><span class="text-white font-semibold">First look, first dibs.</span> Reserving now doesn't charge you anything today. We'll reach out with payment details once your reservation is in.</p>
    </div>

    <div class="bg-[#111111] border border-white/10 rounded-2xl p-6 md:p-7">

        {{-- ---------- Step 1: choose ---------- --}}
        <div x-show="step === 'choose'">
            <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-5">Step 1 of 2 &middot; Choose your item</p>

            @if($product['sizes'])
            <div class="mb-6" x-data="{ chartOpen: false }">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-medium text-gray-300">Size <span class="text-orange-500">*</span></p>
                    <button type="button" @click="chartOpen = !chartOpen" class="text-orange-500 text-xs font-bold underline underline-offset-2">
                        <span x-text="chartOpen ? 'Hide sizing chart ↑' : 'View sizing chart ↓'">View sizing chart ↓</span>
                    </button>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                    @foreach($product['sizes'] as $size)
                    <button type="button" @click="size = @js($size); errors.size = false"
                        :class="size === @js($size) ? 'bg-orange-500/10 border-orange-500 text-white' : 'bg-[#0a0a0a] border-white/10 text-gray-400 hover:border-white/30'"
                        class="py-3 rounded-lg border text-sm font-bold transition-colors">
                        {{ $size }}
                    </button>
                    @endforeach
                </div>
                <p x-show="errors.size" class="text-red-400 text-xs mt-2">Select a size.</p>

                <div x-show="chartOpen" x-transition style="display: none;" class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <caption class="text-left text-gray-500 text-xs mb-2">Asia fit &middot; all measurements in centimeters (cm)</caption>
                        <thead>
                            <tr class="text-xs uppercase tracking-wider text-gray-300">
                                @foreach(['Size', 'Length', 'Chest', 'Shoulder', 'Sleeve'] as $col)
                                <th class="bg-white/5 border border-white/10 px-3 py-2 font-semibold">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product['size_chart'] as $size => $measurements)
                            <tr class="text-center">
                                <td class="border border-white/10 px-3 py-2 text-white font-bold">{{ $size }}</td>
                                @foreach($measurements as $cm)
                                <td class="border border-white/10 px-3 py-2 text-gray-400 tabular-nums">{{ $cm }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="text-gray-500 text-xs mt-2">Measurements may vary by 1&ndash;2cm due to manual measuring.</p>
                </div>
            </div>
            @else
            <div class="bg-[#0a0a0a] border-l-2 border-orange-500 rounded-r-xl px-4 py-3 mb-6">
                <p class="text-gray-400 text-sm"><span class="text-white font-semibold">One size.</span> {{ $product['one_size_note'] }}</p>
            </div>
            @endif

            <div class="mb-6">
                <p class="text-sm font-medium text-gray-300 mb-2">Quantity</p>
                <div class="inline-flex items-center bg-[#0a0a0a] border border-white/10 rounded-lg">
                    <button type="button" @click="quantity = Math.max(1, quantity - 1)" :disabled="quantity <= 1"
                        class="w-11 h-11 text-gray-300 hover:text-white disabled:opacity-30 text-lg" aria-label="Decrease quantity">&minus;</button>
                    <span class="w-10 text-center text-white font-bold tabular-nums" x-text="quantity">1</span>
                    <button type="button" @click="quantity = Math.min(maxQty, quantity + 1)" :disabled="quantity >= maxQty"
                        class="w-11 h-11 text-gray-300 hover:text-white disabled:opacity-30 text-lg" aria-label="Increase quantity">+</button>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-white/10 pt-5 mb-5">
                <span class="text-gray-400 text-sm">Total</span>
                <span class="text-white font-extrabold text-xl" x-text="peso(price * quantity)">&#8369;{{ number_format($product['price']) }}</span>
            </div>

            <button type="button" @click="continueToDetails()"
                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                Reserve Yours &rarr;
            </button>
            <p class="text-gray-500 text-xs text-center mt-3">No payment today. You'll fill in your details next.</p>
        </div>

        {{-- ---------- Step 2: details ---------- --}}
        <form x-show="step === 'details'" style="display: none;" @submit.prevent="submit()" novalidate>
            <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-5">Step 2 of 2 &middot; Your details</p>

            <div class="flex items-center justify-between gap-4 bg-[#0a0a0a] border border-white/10 rounded-xl px-4 py-3 mb-6">
                <div>
                    <p class="text-white font-semibold text-sm" x-text="lineLabel()"></p>
                    <p class="text-gray-500 text-xs mt-0.5" x-text="peso(price * quantity)"></p>
                </div>
                <button type="button" @click="step = 'choose'" class="text-orange-500 text-xs font-bold underline underline-offset-2">Change</button>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label for="{{ $id('full_name') }}" class="{{ $label }}">Full name <span class="text-orange-500">*</span></label>
                    <input type="text" id="{{ $id('full_name') }}" x-model="form.full_name" autocomplete="name" class="{{ $input }}" :class="errors.full_name && '!border-red-500'">
                    <p x-show="errors.full_name" class="text-red-400 text-xs mt-1.5">Enter your full name.</p>
                </div>
                <div>
                    <label for="{{ $id('email') }}" class="{{ $label }}">Email <span class="text-orange-500">*</span></label>
                    <input type="email" id="{{ $id('email') }}" x-model="form.email" autocomplete="email" class="{{ $input }}" :class="errors.email && '!border-red-500'">
                    <p x-show="errors.email" class="text-red-400 text-xs mt-1.5">Enter a valid email address.</p>
                </div>
                <div>
                    <label for="{{ $id('mobile_number') }}" class="{{ $label }}">Mobile number <span class="text-orange-500">*</span></label>
                    <input type="tel" id="{{ $id('mobile_number') }}" x-model="form.mobile_number" autocomplete="tel" placeholder="09XX XXX XXXX" class="{{ $input }}" :class="errors.mobile_number && '!border-red-500'">
                    <p x-show="errors.mobile_number" class="text-red-400 text-xs mt-1.5">Enter your mobile number.</p>
                </div>
            </div>

            <label class="flex items-start gap-3 cursor-pointer mb-1">
                <input type="checkbox" x-model="form.notify_consent" class="mt-0.5 rounded border-white/20 bg-[#0a0a0a] text-orange-600 focus:ring-orange-500 focus:ring-offset-0">
                <span class="text-sm text-gray-300">Contact me by email and SMS about this order, including payment instructions.</span>
            </label>
            <p x-show="errors.notify_consent" class="text-red-400 text-xs mb-1">Please agree so we can reach you about your order.</p>

            <p x-show="submitError" x-text="submitError" class="text-red-400 text-sm mt-4" style="display: none;"></p>

            <button type="submit" :disabled="submitting"
                class="mt-6 w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                x-text="submitting ? 'Reserving…' : 'Reserve My ' + name.split(' ').pop()">
                Reserve
            </button>
            <p class="text-gray-500 text-xs text-center mt-3">Reserving doesn't charge you anything today.</p>
        </form>

        {{-- ---------- Done ---------- --}}
        <div x-show="step === 'done'" style="display: none;">
            <div class="w-12 h-12 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center mb-5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h4 class="text-2xl font-bold text-white mb-2">You're on the list</h4>
            <p class="text-gray-400 text-sm leading-relaxed mb-5">Thanks for getting the first look. We'll email and text you with payment details for your reservation. Keep your order reference handy.</p>
            <dl class="bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 text-sm grid grid-cols-[auto_1fr] gap-x-6 gap-y-2 mb-6">
                <dt class="text-gray-500">Reference</dt>
                <dd class="text-orange-500 font-bold tracking-wide" x-text="reference"></dd>
                <dt class="text-gray-500">Item</dt>
                <dd class="text-gray-200" x-text="lineLabel()"></dd>
                <dt class="text-gray-500">Total</dt>
                <dd class="text-gray-200" x-text="peso(price * quantity)"></dd>
            </dl>
            <button type="button" @click="reset()" class="w-full py-3 px-4 inline-flex justify-center items-center text-sm font-bold rounded-lg border border-white/20 text-white hover:border-orange-500 hover:text-orange-500 transition-colors">
                Reserve another
            </button>
        </div>
    </div>
</div>

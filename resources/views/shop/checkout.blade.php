@extends('layouts.public')
@section('title', 'Checkout · Shop')

@php
$input = 'w-full bg-[#1f2937] border border-gray-700 rounded-lg text-white text-sm px-4 py-3 placeholder-gray-500 focus:border-orange-500 focus:ring-0 focus:outline-none transition-colors';
$label = 'block text-sm font-semibold text-white mb-2';
$groupTitle = 'text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-5';
$err = fn(string $field) => $errors->has($field) ? '!border-orange-500' : '';
@endphp

@section('content')
<section class="bg-[#0a0a0a] pt-32 pb-20">
    <div class="mx-auto px-6 md:px-8" style="max-width:860px;"
        x-data="shopCheckout(@js([
            'areas' => collect($shippingAreas)->map(fn($a) => $a['fee']),
            'area'  => old('shipping_area', ''),
        ]))">

        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Continue shopping
        </a>

        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-4">Checkout</p>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Complete Your Order</h1>
        <p class="text-gray-400 max-w-2xl mb-10">Review your cart, choose your shipping area, then pay in full via bank transfer and upload your receipt to confirm.</p>

        {{-- Empty cart --}}
        <div x-show="$store.cart.count() === 0" style="display: none;" class="bg-[#111111] border border-white/10 rounded-2xl p-10 text-center">
            <p class="text-white font-bold text-lg mb-2">Your cart is empty</p>
            <p class="text-gray-400 text-sm mb-6">Add the cap or tee to your cart to check out.</p>
            <a href="{{ route('shop.index') }}" class="py-3 px-5 inline-flex items-center text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">Browse the Shop</a>
        </div>

        <form x-show="$store.cart.count() > 0" method="POST" action="{{ route('shop.store') }}" enctype="multipart/form-data"
            @submit="submitting = true"
            class="bg-[#111111] border border-white/10 rounded-2xl p-6 md:p-8">
            @csrf

            @if($errors->any())
            <div class="bg-orange-500/10 border border-orange-500/40 rounded-lg px-4 py-3 mb-8 text-sm">
                <p class="text-white font-semibold mb-1">Please check your order</p>
                <ul class="list-disc pl-5 text-orange-300 space-y-0.5">
                    @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                    @endforeach
                </ul>
                @if(! $errors->has('proof_of_payment'))
                <p class="text-gray-400 text-xs mt-2">For your security, please re-attach your proof of payment.</p>
                @endif
            </div>
            @endif

            {{-- ---------- Cart ---------- --}}
            <div class="{{ $groupTitle }}">Your Cart</div>
            <p x-show="$store.cart.notice" x-text="$store.cart.notice" class="text-amber-400 text-xs mb-3" style="display: none;"></p>
            <ul class="space-y-2.5">
                <template x-for="(line, i) in $store.cart.lines()" :key="line.id">
                    <li class="flex items-center gap-3 bg-[#1f2937] border border-gray-700 rounded-lg p-3">
                        <input type="hidden" :name="'items[' + i + '][variant_id]'" :value="line.id">
                        <input type="hidden" :name="'items[' + i + '][quantity]'" :value="line.qty">
                        <div class="w-14 h-14 rounded-md bg-[#c9c9c9] flex-shrink-0 overflow-hidden p-1">
                            <img :src="line.image" :alt="line.name" class="w-full h-full object-contain" x-show="line.image">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-bold truncate" x-text="line.name"></p>
                            <p class="text-gray-400 text-xs" x-text="$store.cart.options(line) + ' · ' + $store.cart.peso(line.price) + ' each'"></p>
                        </div>
                        <div class="inline-flex items-center border border-gray-600 rounded-lg">
                            <button type="button" @click="$store.cart.setQty(line.id, line.qty - 1)" :disabled="line.qty <= 1"
                                class="w-8 h-8 text-gray-300 hover:text-white disabled:opacity-30" aria-label="Decrease quantity">&minus;</button>
                            <span class="w-7 text-center text-white text-sm font-bold tabular-nums" x-text="line.qty"></span>
                            <button type="button" @click="$store.cart.setQty(line.id, line.qty + 1)" :disabled="line.qty >= $store.cart.limit(line.id)"
                                class="w-8 h-8 text-gray-300 hover:text-white disabled:opacity-30" aria-label="Increase quantity">+</button>
                        </div>
                        <span class="hidden sm:block w-24 text-right text-orange-500 text-sm font-bold" x-text="$store.cart.peso(line.total)"></span>
                        <button type="button" @click="$store.cart.remove(line.id)" class="text-gray-500 hover:text-orange-500 text-xl leading-none p-1" aria-label="Remove item">&times;</button>
                    </li>
                </template>
            </ul>
            <div class="flex justify-between font-bold text-white text-[15px] mt-3 pt-3 border-t border-white/10">
                <span>Subtotal</span>
                <span x-text="$store.cart.peso($store.cart.subtotal())"></span>
            </div>

            {{-- ---------- Details ---------- --}}
            <div class="{{ $groupTitle }} mt-10">Your Details</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-5">
                <div>
                    <label for="full_name" class="{{ $label }}">Full Name <span class="text-orange-500">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required autocomplete="name" class="{{ $input }} {{ $err('full_name') }}">
                    @error('full_name') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="{{ $label }}">Email Address <span class="text-orange-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="{{ $input }} {{ $err('email') }}">
                    @error('email') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="mobile_number" class="{{ $label }}">Mobile Number <span class="text-orange-500">*</span></label>
                    <input type="tel" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" required autocomplete="tel" placeholder="09XX XXX XXXX" class="{{ $input }} {{ $err('mobile_number') }}">
                    @error('mobile_number') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="shipping_area" class="{{ $label }}">Shipping Area <span class="text-orange-500">*</span></label>
                    <select id="shipping_area" name="shipping_area" x-model="area" required class="{{ $input }} {{ $err('shipping_area') }}">
                        <option value="">Select your area&hellip;</option>
                        @foreach($shippingAreas as $key => $area)
                        <option value="{{ $key }}">{{ $area['label'] }} &middot; {{ $area['fee'] ? '₱' . number_format($area['fee']) : 'Free' }}</option>
                        @endforeach
                    </select>
                    @error('shipping_area') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-3">Estimated LBC Express door-to-door rate for a ~1kg package from Baguio City. Final rate is confirmed at LBC drop-off and may vary by exact barangay or municipality.</p>

            {{-- Delivery address, hidden for pickup --}}
            <div x-show="area && area !== 'pickup'" x-transition style="display: none;" class="mt-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-5">
                    <div class="sm:col-span-2">
                        <label for="address_line" class="{{ $label }}">Street Address <span class="text-orange-500">*</span></label>
                        <input type="text" id="address_line" name="address_line" value="{{ old('address_line') }}" autocomplete="street-address"
                            placeholder="House / unit no., street, barangay" class="{{ $input }} {{ $err('address_line') }}">
                        @error('address_line') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city" class="{{ $label }}">City / Municipality <span class="text-orange-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" autocomplete="address-level2" class="{{ $input }} {{ $err('city') }}">
                        @error('city') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="province" class="{{ $label }}">Province <span class="text-orange-500">*</span></label>
                        <input type="text" id="province" name="province" value="{{ old('province') }}" autocomplete="address-level1" class="{{ $input }} {{ $err('province') }}">
                        @error('province') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="postal_code" class="{{ $label }}">ZIP Code <span class="text-orange-500">*</span></label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" autocomplete="postal-code" inputmode="numeric" class="{{ $input }} {{ $err('postal_code') }}">
                        @error('postal_code') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div x-show="area === 'pickup'" style="display: none;" class="mt-6 bg-[#161616] border-l-[3px] border-orange-500 rounded-r-lg px-5 py-4">
                <p class="text-gray-400 text-sm"><span class="text-white font-semibold">Pickup in Baguio City.</span> We'll message you with the pickup details once your payment is confirmed.</p>
            </div>

            {{-- ---------- Summary ---------- --}}
            <div class="{{ $groupTitle }} mt-10">Order Summary</div>
            <div class="space-y-1.5">
                <div class="flex justify-between text-sm text-gray-400"><span>Merch Subtotal</span><span x-text="$store.cart.peso($store.cart.subtotal())"></span></div>
                <div class="flex justify-between text-sm text-gray-400"><span>Shipping (LBC Express)</span><span x-text="area ? $store.cart.peso(shipping()) : 'Select an area'"></span></div>
                <div class="flex justify-between font-bold text-white text-[15px] pt-2 mt-1 border-t border-white/10"><span>Total to Pay</span><span x-text="$store.cart.peso(total())"></span></div>
            </div>
            <div class="flex items-center justify-between bg-orange-500/10 border border-orange-500/35 rounded-lg px-5 py-4 mt-5">
                <span class="text-gray-400 text-sm font-bold uppercase tracking-wide">Amount Due Now</span>
                <span class="text-orange-500 font-extrabold text-2xl" x-text="$store.cart.peso(total())"></span>
            </div>

            {{-- ---------- Bank ---------- --}}
            <div class="{{ $groupTitle }} mt-10">Bank Transfer</div>
            <div class="bg-[#1f2937] border border-gray-700 rounded-lg px-5 py-4">
                @foreach(['Bank' => $bank['name'], 'Account Name' => $bank['account_name'], 'Account Number' => $bank['account_number']] as $k => $v)
                <div class="flex justify-between gap-3 py-2 text-sm {{ ! $loop->last ? 'border-b border-white/10' : '' }}">
                    <span class="text-gray-400">{{ $k }}</span>
                    <span class="text-white font-bold text-right">{{ $v }}</span>
                </div>
                @endforeach
                <p class="text-gray-500 text-xs mt-3">Send the total shown above to this account (covers merch + shipping), then upload your proof of payment below.</p>
            </div>

            {{-- ---------- Proof ---------- --}}
            <div class="{{ $groupTitle }} mt-10">Proof of Payment</div>
            <label for="proof_of_payment"
                @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="drop($event)"
                :class="fileName ? 'border-green-400 border-solid' : (dragging ? 'border-orange-500' : '{{ $errors->has('proof_of_payment') ? 'border-orange-500' : 'border-gray-700' }}')"
                class="flex flex-col items-center justify-center gap-1 border-[1.5px] border-dashed rounded-lg px-4 py-7 cursor-pointer text-center hover:border-orange-500 transition-colors">
                <span class="text-white text-sm font-semibold" x-text="fileName || 'Click to upload or drag and drop'">Click to upload or drag and drop</span>
                <span class="text-gray-500 text-xs">JPG, PNG &middot; max 5MB</span>
                <input type="file" id="proof_of_payment" name="proof_of_payment" x-ref="proof" accept="image/jpeg,image/png" required class="sr-only" @change="fileName = $event.target.files[0]?.name || ''">
            </label>
            @error('proof_of_payment') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror

            <label class="flex items-start gap-3 cursor-pointer mt-6">
                <input type="checkbox" name="payment_consent" value="1" @checked(old('payment_consent')) required
                    class="mt-1 rounded border-gray-600 bg-[#1f2937] text-orange-600 focus:ring-orange-500 focus:ring-offset-0">
                <span class="text-sm text-gray-400">I confirm I've sent the total amount above via bank transfer and the proof of payment uploaded is accurate.</span>
            </label>
            @error('payment_consent') <p class="text-orange-500 text-xs mt-1.5">{{ $message }}</p> @enderror

            <button type="submit" :disabled="submitting || $store.cart.count() === 0"
                class="mt-7 w-full py-3.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:pointer-events-none">
                <span x-text="submitting ? 'Placing your order…' : 'Confirm Order'">Confirm Order</span>
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
@include('shop._cart_store')
<script>
    function shopCheckout(config) {
        return {
            areas: config.areas,
            area: config.area,
            fileName: '',
            dragging: false,
            submitting: false,

            shipping() {
                return this.$store.cart.count() > 0 ? (this.areas[this.area] ?? 0) : 0;
            },

            total() {
                return this.$store.cart.subtotal() + this.shipping();
            },

            drop(event) {
                this.dragging = false;
                const file = event.dataTransfer.files[0];
                if (!file) return;
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.proof.files = dt.files;
                this.fileName = file.name;
            },
        };
    }
</script>
@endpush

{{--
    Sticky order summary.

    $showDiscountCode  — individual registration only; the group page has no codes.
    $showTierProgress  — group registration only; a party of one can never reach a tier.
--}}
@php
    $showDiscountCode = $showDiscountCode ?? false;
    $showTierProgress = $showTierProgress ?? false;
@endphp

<aside x-show="!reviewing" class="lg:col-span-1 lg:row-span-2 lg:self-stretch">
    <div class="lg:sticky lg:top-6 flex flex-col gap-4">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">Order Summary</h2>
                <span class="text-xs font-medium text-gray-500"
                    x-text="participants.length + (participants.length === 1 ? ' person' : ' people')"></span>
            </div>

            <div class="p-5 flex flex-col gap-4">
                {{-- Line items --}}
                <ul class="flex flex-col gap-2 text-sm">
                    <template x-for="(p, i) in participants" :key="p._id">
                        <li class="flex justify-between gap-2">
                            <span class="text-gray-600 truncate">
                                <span x-text="participantLabel(i)"></span>
                                <span class="text-gray-400" x-show="categoryName(i)" x-cloak>
                                    · <span x-text="categoryName(i)"></span>
                                </span>
                            </span>
                            <span class="font-medium text-gray-900 flex-shrink-0"
                                x-text="p?.race_category_id ? formatPHP(priceFor(i)) : '—'"></span>
                        </li>
                    </template>
                </ul>

                <div class="border-t border-gray-100 pt-3 flex flex-col gap-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Subtotal</dt>
                        <dd class="font-medium text-gray-900" x-text="formatPHP(subtotal)"></dd>
                    </div>
                    <div x-show="discountSource === 'group'" x-cloak class="flex justify-between">
                        <dt class="text-gray-600">Group discount (<span x-text="groupPercentage"></span>%)</dt>
                        <dd class="font-medium text-green-600">−<span x-text="formatPHP(groupDiscount)"></span></dd>
                    </div>
                    <div x-show="discountSource === 'code'" x-cloak class="flex justify-between gap-2">
                        <dt class="text-gray-600 min-w-0">
                            Code <span class="font-mono text-xs" x-text="appliedDiscount?.code"></span>
                        </dt>
                        <dd class="font-medium text-green-600 flex-shrink-0">−<span x-text="formatPHP(codeDiscount)"></span></dd>
                    </div>
                    <div class="border-t border-gray-100 pt-2 flex justify-between items-baseline">
                        <dt class="font-semibold text-gray-900">Total Due</dt>
                        <dd class="font-bold text-lg text-orange-600" x-text="formatPHP(totalDue)"></dd>
                    </div>
                </div>

                @if($showTierProgress)
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-3.5">
                    <template x-if="groupPercentage > 0">
                        <p class="text-xs font-semibold text-green-700 flex items-center gap-1.5 mb-2">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="groupPercentage + '% group discount applied'"></span>
                        </p>
                    </template>
                    <template x-if="nextTier">
                        <p class="text-xs text-gray-600 mb-2">
                            Add <span class="font-semibold text-gray-900" x-text="nextTier.needed"></span>
                            more <span x-text="nextTier.needed === 1 ? 'person' : 'people'"></span>
                            for <span class="font-semibold text-green-600" x-text="nextTier.percentage + '% off'"></span>
                        </p>
                    </template>
                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full transition-all duration-300"
                            :style="'width: ' + tierProgress + '%'"></div>
                    </div>
                    <div class="flex justify-between mt-1.5 text-[10px] text-gray-400">
                        <span>5</span>
                        <span :class="participants.length >= 5 ? 'text-green-600 font-semibold' : ''">5 · 5%</span>
                        <span :class="participants.length >= 10 ? 'text-green-600 font-semibold' : ''">10 · 10%</span>
                    </div>
                </div>
                @endif

                @if($showDiscountCode)
                <div class="border-t border-gray-100 pt-4">
                    <label for="discount_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Discount Code <span class="text-gray-400 text-xs font-normal">(optional)</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="discount_code" name="discount_code"
                            x-model="discount_code"
                            :readonly="appliedDiscount !== null"
                            style="text-transform:uppercase"
                            placeholder="Enter code"
                            class="flex-1 min-w-0 rounded-lg border border-gray-200 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent read-only:bg-gray-50 read-only:text-gray-500 font-mono" />
                        <button type="button"
                            x-show="appliedDiscount === null"
                            @click="applyDiscount()"
                            :disabled="discountChecking || !hasAnyCategory || !discount_code"
                            class="px-3.5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex-shrink-0">
                            <span x-show="!discountChecking">Apply</span>
                            <span x-show="discountChecking" x-cloak>…</span>
                        </button>
                        <button type="button"
                            x-show="appliedDiscount !== null" x-cloak
                            @click="removeDiscount()"
                            class="px-3.5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors flex-shrink-0">
                            Remove
                        </button>
                    </div>
                    <p x-show="discountError" x-cloak x-text="discountError" class="text-xs text-red-500 mt-1"></p>
                    <p x-show="appliedDiscount !== null" x-cloak class="text-xs text-green-600 mt-1">
                        Code applied — <span x-text="appliedDiscount?.percentage"></span>% off.
                    </p>
                    @error('discount_code')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>
        </div>
    </div>
</aside>

{{-- Mobile sticky total bar --}}
<div x-show="!reviewing" class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-200 shadow-lg px-4 py-3">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs text-gray-500">
                <span x-text="participants.length"></span>
                <span x-text="participants.length === 1 ? 'participant' : 'participants'"></span>
                <span x-show="groupPercentage > 0" x-cloak class="text-green-600 font-semibold">
                    · <span x-text="groupPercentage"></span>% off
                </span>
            </p>
            <p class="text-lg font-bold text-orange-600 leading-tight" x-text="formatPHP(totalDue)"></p>
        </div>
        <button type="button" @click="showReview()"
            class="px-5 py-2.5 bg-orange-600 text-white text-sm font-semibold rounded-xl active:scale-95 transition-all">
            Review
        </button>
    </div>
</div>

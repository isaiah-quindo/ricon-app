{{-- REVIEW PANEL                                                 --}}
{{-- ============================================================ --}}
<div x-show="reviewing" x-cloak class="lg:col-span-3 space-y-4">

    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-orange-800">Please review before submitting.</p>
        <p class="text-xs text-orange-600 mt-0.5">Click "Edit" to go back and make changes.</p>
    </div>

    {{-- Participants --}}
    <template x-for="(p, i) in participants" :key="'review-' + p._id">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0" x-text="i + 1"></span>
                    <span x-text="participantLabel(i)"></span>
                </h2>
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-900" x-text="categoryName(i) || '—'"></p>
                    <p class="text-xs text-gray-500" x-text="formatPHP(priceFor(i))"></p>
                </div>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Sex</dt>
                        <dd class="font-medium text-gray-800" x-text="titleCase(p?.sex)"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Birthdate</dt>
                        <dd class="font-medium text-gray-800" x-text="formatDate(p?.birthdate)"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Email</dt>
                        <dd class="font-medium text-gray-800 break-all" x-text="p?.email || '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Mobile</dt>
                        <dd class="font-medium text-gray-800" x-text="p?.mobile_number || '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Shirt Size</dt>
                        <dd class="font-medium text-gray-800" x-text="p?.shirt_size || '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Nationality</dt>
                        <dd class="font-medium text-gray-800" x-text="p?.nationality || '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Club / Affiliation</dt>
                        <dd class="font-medium text-gray-800" x-text="p?.affiliation || '—'"></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Emergency Contact</dt>
                        <dd class="font-medium text-gray-800">
                            <span x-text="p?.emergency_contact_name || '—'"></span>
                            <span class="text-gray-500" x-show="p?.emergency_contact_number" x-cloak>
                                · <span x-text="p?.emergency_contact_number"></span>
                            </span>
                        </dd>
                    </div>
                    <div class="col-span-2 sm:col-span-3 lg:col-span-4">
                        <dt class="text-xs text-gray-400 mb-0.5">Address</dt>
                        <dd class="font-medium text-gray-800" x-text="p?.address || '—'"></dd>
                    </div>
                </dl>
            </div>
        </div>
    </template>

    {{-- Payment + totals --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-semibold text-gray-800">Payment</h2>
        </div>
        <div class="p-6 space-y-4">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-xs text-gray-400 mb-0.5">Payment Method</dt>
                    <dd class="font-medium text-gray-800" x-text="payment_method || '—'"></dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 mb-0.5">Proof of Payment</dt>
                    <dd class="font-medium text-gray-800 break-all" x-text="fileName || '—'"></dd>
                </div>
            </dl>

            <div class="border-t border-gray-100 pt-4">
                <dl class="space-y-1.5 text-sm max-w-sm ms-auto">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Subtotal (<span x-text="participants.length"></span>)</dt>
                        <dd class="font-medium text-gray-800" x-text="formatPHP(subtotal)"></dd>
                    </div>
                    <div x-show="discountSource === 'group'" x-cloak class="flex justify-between">
                        <dt class="text-gray-500">Group discount (<span x-text="groupPercentage"></span>%)</dt>
                        <dd class="font-medium text-green-600">−<span x-text="formatPHP(groupDiscount)"></span></dd>
                    </div>
                    <div x-show="discountSource === 'code'" x-cloak class="flex justify-between gap-2">
                        <dt class="text-gray-500 min-w-0">
                            Discount code (<span class="font-mono" x-text="appliedDiscount?.code"></span>)
                        </dt>
                        <dd class="font-medium text-green-600 flex-shrink-0">−<span x-text="formatPHP(codeDiscount)"></span></dd>
                    </div>
                    <div class="flex justify-between items-baseline pt-1.5 border-t border-gray-100">
                        <dt class="font-semibold text-gray-900">Total to Pay</dt>
                        <dd class="font-bold text-lg text-orange-600" x-text="formatPHP(totalDue)"></dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- Agreements --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-semibold text-gray-800">Agreements</h2>
        </div>
        <div class="p-6 space-y-2 text-sm text-gray-700">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Liability Waiver agreed
                <span x-show="participants.length > 1" x-cloak class="text-gray-500">for all participants</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Rules and Conditions agreed
                <span x-show="participants.length > 1" x-cloak class="text-gray-500">for all participants</span>
            </div>
        </div>
    </div>

    {{-- Review actions --}}
    <div class="flex flex-col sm:flex-row items-center gap-3">
        <button type="button" @click="backToForm()"
            class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 active:scale-95 transition-all">
            &larr; Edit
        </button>
        <button type="submit"
            :disabled="submitting"
            class="w-full sm:w-auto px-8 py-3.5 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-600/20 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:bg-orange-600 disabled:active:scale-100 inline-flex items-center justify-center gap-2">
            <svg x-show="submitting" x-cloak class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span x-text="submitting ? 'Submitting…' : 'Confirm & Submit'">Confirm &amp; Submit</span>
        </button>
    </div>

</div>

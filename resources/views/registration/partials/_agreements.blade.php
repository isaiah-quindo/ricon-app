{{-- Waiver + terms. Copy switches on participants.length so the organizer confirms
     on everyone's behalf. --}}
{{-- ---------------------------------------------------- --}}
{{-- Agreements                                            --}}
{{-- ---------------------------------------------------- --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-sm font-semibold text-gray-800">Agreements</h2>
    </div>
    <div class="p-6 space-y-4">
        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="waiver_agreed" value="1" x-model="waiver_agreed"
                class="mt-0.5 w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500 flex-shrink-0">
            <span class="text-sm text-gray-700">
                <template x-if="participants.length > 1">
                    <span>I confirm that <span class="font-semibold text-gray-900">every participant listed above</span> has read and agrees to the</span>
                </template>
                <template x-if="participants.length === 1">
                    <span>I agree to the</span>
                </template>
                <button type="button" @click.prevent="showWaiver = true" class="font-semibold text-orange-500 underline underline-offset-2 hover:text-orange-600 transition-colors">Liability Waiver</button>.
                Trail running involves risks, and those risks are voluntarily assumed.
            </span>
        </label>
        <p x-show="clientErrors.waiver" x-cloak class="text-xs text-red-500">You must agree to the liability waiver.</p>
        @error('waiver_agreed')
        <p class="text-xs text-red-500">{{ $message }}</p>
        @enderror

        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="terms_agreed" value="1" x-model="terms_agreed"
                class="mt-0.5 w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500 flex-shrink-0">
            <span class="text-sm text-gray-700">
                <template x-if="participants.length > 1">
                    <span>I confirm that <span class="font-semibold text-gray-900">every participant listed above</span> agrees to the</span>
                </template>
                <template x-if="participants.length === 1">
                    <span>I agree to the</span>
                </template>
                <a href="/rules" target="_blank" class="font-semibold text-orange-500 underline">Rules and Conditions</a>
                of this race, including race rules, cutoff times, and disqualification policies.
            </span>
        </label>
        <p x-show="clientErrors.terms" x-cloak class="text-xs text-red-500">You must agree to the rules and conditions.</p>
        @error('terms_agreed')
        <p class="text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>

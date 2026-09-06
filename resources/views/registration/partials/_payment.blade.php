{{-- Payment instructions, bank/QR panel, method and proof upload. One proof covers
     the whole submission, however many participants it holds. --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-sm font-semibold text-gray-800">Payment</h2>
    </div>
    <div class="p-6 space-y-4">
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 text-sm text-indigo-800">
            <p class="font-semibold mb-1">Payment Instructions</p>
            <p class="text-indigo-700">
                Send
                <span class="font-semibold" x-text="formatPHP(totalDue)"></span>
                <span x-show="participants.length > 1" x-cloak>for all <span x-text="participants.length"></span> participants</span>
                to the account below, then upload one proof of payment.
            </p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm">
            <p class="font-semibold text-gray-800 mb-3">Bank Transfer</p>
            <dl class="space-y-2">
                <div>
                    <dt class="text-gray-500 text-xs">Bank</dt>
                    <dd class="font-medium text-gray-800">Rizal Commercial Banking Corporation (RCBC)</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs">Account Name</dt>
                    <dd class="font-medium text-gray-800">RiCON</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs">Account Number</dt>
                    <dd class="font-medium text-gray-800 tracking-wider">7-591-41115-4</dd>
                </div>
            </dl>
        </div>

        <div>
            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1.5">
                Payment Method
            </label>
            <select id="payment_method" name="payment_method" x-model="payment_method"
                class="w-full rounded-lg border border-gray-200 text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white">
                <option value="">Select method</option>
                <option value="GCash">GCash</option>
                <option value="Maya">Maya</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div>
            <label for="proof_of_payment" class="block text-sm font-medium text-gray-700 mb-1.5">
                Proof of Payment <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed rounded-xl p-6 text-center transition-colors"
                :class="(clientErrors.proof || serverHas('proof_of_payment')) ? 'border-red-300 bg-red-50' : 'border-gray-200 hover:border-orange-300'">
                <input type="file" id="proof_of_payment" name="proof_of_payment"
                    accept="image/jpeg,image/png"
                    @change="fileName = $event.target.files[0]?.name; clientErrors.proof = false"
                    class="sr-only" />
                <label for="proof_of_payment" class="cursor-pointer">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p x-show="!fileName" class="text-sm text-gray-500">
                        <span class="font-medium text-orange-600">Click to upload</span> or drag and drop
                    </p>
                    <p x-show="fileName" x-cloak class="text-sm font-medium text-orange-600" x-text="fileName"></p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG — max 5MB</p>
                </label>
            </div>
            @error('proof_of_payment')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

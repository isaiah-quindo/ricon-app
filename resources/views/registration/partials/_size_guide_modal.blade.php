{{-- Size Guide Modal --}}
<div
    x-show="sizeGuideOpen"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4"
    @keydown.escape.window="sizeGuideOpen = false">

    <div
        class="absolute inset-0 bg-black/70 backdrop-blur-sm"
        @click="sizeGuideOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <div
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] flex flex-col"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">

        <div class="flex items-center justify-between px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-100 flex-shrink-0">
            <div>
                <h2 class="text-base font-bold text-gray-900">Shirt Size Guide</h2>
                <p class="text-xs text-gray-500 mt-0.5">All measurements in inches</p>
            </div>
            <button type="button" @click="sizeGuideOpen = false"
                class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100"
                aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto flex-1">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-2.5 text-left text-xs text-gray-500 font-medium border-r border-gray-200">Measurement</th>
                            <th class="px-3 py-2.5 font-semibold text-gray-700">XS</th>
                            <th class="px-3 py-2.5 font-semibold text-gray-700">S</th>
                            <th class="px-3 py-2.5 font-semibold text-gray-700">M</th>
                            <th class="px-3 py-2.5 font-semibold text-gray-700">L</th>
                            <th class="px-3 py-2.5 font-semibold text-gray-700">XL</th>
                            <th class="px-3 py-2.5 font-semibold text-gray-700">2XL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="px-4 py-2.5 text-left text-xs text-gray-500 font-medium border-r border-gray-200">Shirt Length</td>
                            <td class="px-3 py-2.5 text-gray-700">25</td>
                            <td class="px-3 py-2.5 text-gray-700">26</td>
                            <td class="px-3 py-2.5 text-gray-700">27</td>
                            <td class="px-3 py-2.5 text-gray-700">29</td>
                            <td class="px-3 py-2.5 text-gray-700">30</td>
                            <td class="px-3 py-2.5 text-gray-700">31</td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="px-4 py-2.5 text-left text-xs text-gray-500 font-medium border-r border-gray-200">Chest Circumference</td>
                            <td class="px-3 py-2.5 text-gray-700">36</td>
                            <td class="px-3 py-2.5 text-gray-700">38</td>
                            <td class="px-3 py-2.5 text-gray-700">40</td>
                            <td class="px-3 py-2.5 text-gray-700">42</td>
                            <td class="px-3 py-2.5 text-gray-700">44</td>
                            <td class="px-3 py-2.5 text-gray-700">46</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5 text-left text-xs text-gray-500 font-medium border-r border-gray-200">Sleeve Length</td>
                            <td class="px-3 py-2.5 text-gray-700">7</td>
                            <td class="px-3 py-2.5 text-gray-700">7.5</td>
                            <td class="px-3 py-2.5 text-gray-700">8</td>
                            <td class="px-3 py-2.5 text-gray-700">8.5</td>
                            <td class="px-3 py-2.5 text-gray-700">9</td>
                            <td class="px-3 py-2.5 text-gray-700">10</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-gray-500 text-center px-4 py-3">+/- 1 inch for bigger or smaller sizes</p>
        </div>

        <div class="px-4 py-3 sm:px-6 sm:py-4 border-t border-gray-100 flex-shrink-0 flex items-center justify-end">
            <button type="button" @click="sizeGuideOpen = false"
                class="px-4 py-2 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors">
                Got it
            </button>
        </div>
    </div>
</div>

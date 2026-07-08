<div id="signup" class="scroll-mt-24 bg-[#0d0d0d]/85 backdrop-blur border border-white/10 rounded-2xl p-8" x-data="trainingSignup()">
    {{-- Toast --}}
    <div x-show="toast.visible" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-3"
        class="fixed top-6 inset-x-4 sm:inset-x-0 sm:mx-auto sm:w-full sm:max-w-sm z-[60]"
        role="status" aria-live="polite">
        <div class="flex items-start gap-3 rounded-xl border bg-[#111111] px-4 py-3.5 shadow-2xl shadow-black/50"
            :class="toast.type === 'error' ? 'border-red-500/40' : 'border-blue-500/40'">
            <template x-if="toast.type === 'error'">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="toast.type !== 'error'">
                <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <p class="flex-1 text-sm text-gray-200 leading-snug" x-text="toast.message"></p>
            <button type="button" @click="toast.visible = false" aria-label="Dismiss"
                class="text-gray-500 hover:text-white transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Expired-link notice --}}
    <div x-show="expired" x-cloak class="mb-6 rounded-lg border border-blue-500/40 bg-blue-500/10 px-4 py-3 text-sm text-blue-300">
        That link doesn't seem to be active anymore. Sign up again or re-send your link below.
    </div>

    {{-- Already-signed-up state --}}
    <template x-if="done === 'existing'">
        <div class="text-center py-8">
            <div class="w-14 h-14 mx-auto rounded-full bg-blue-500/15 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-2">You've already started a program</h3>
            <p class="text-gray-400 text-sm">We just re-sent your personal link to <strong class="text-white" x-text="email"></strong>. Check your inbox.</p>
        </div>
    </template>

    {{-- Signup form --}}
    <form x-show="done !== 'existing'" @submit.prevent="submit" class="space-y-5">
        <div>
            <p class="text-white font-bold text-lg">Join free. You'll be in Week {{ $currentWeek }} today.</p>
        </div>
        <div>
            <label for="gateName" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">First name</label>
            <input id="gateName" type="text" x-model="name" autocomplete="given-name" placeholder="Your first name"
                class="w-full rounded-lg border border-white/10 bg-white/5 text-white text-sm px-3.5 py-2.5 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>
        <div>
            <label for="gateEmail" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Email address</label>
            <input id="gateEmail" type="email" x-model="email" autocomplete="email" placeholder="you@example.com"
                class="w-full rounded-lg border border-white/10 bg-white/5 text-white text-sm px-3.5 py-2.5 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            <p class="text-gray-600 text-xs mt-1.5">We'll email your personal program link here.</p>
        </div>

        <div>
            <p class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Are you registered for The Great Cordillera?</p>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" @click="registered = false"
                    :class="!registered ? 'border-blue-500 bg-blue-500/10 text-white' : 'border-white/10 text-gray-400 hover:border-white/30'"
                    class="rounded-lg border px-4 py-3 text-left transition-colors">
                    <span class="block text-sm font-bold">Not yet</span>
                    <span class="block text-xs opacity-60">Still deciding</span>
                </button>
                <button type="button" @click="registered = true"
                    :class="registered ? 'border-blue-500 bg-blue-500/10 text-white' : 'border-white/10 text-gray-400 hover:border-white/30'"
                    class="rounded-lg border px-4 py-3 text-left transition-colors">
                    <span class="block text-sm font-bold">Yes</span>
                    <span class="block text-xs opacity-60">I'm in</span>
                </button>
            </div>
        </div>

        <div>
            <p class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Your distance</p>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" @click="plan = 'tgc100k'"
                    :class="plan === 'tgc100k' ? 'border-blue-500 bg-blue-500/10 text-white' : 'border-white/10 text-gray-400 hover:border-white/30'"
                    class="rounded-lg border px-4 py-3 text-left transition-colors">
                    <span class="block text-sm font-bold">100K</span>
                    <span class="block text-xs opacity-60">7,000m vert</span>
                </button>
                <button type="button" @click="plan = 'tgc60k'"
                    :class="plan === 'tgc60k' ? 'border-blue-500 bg-blue-500/10 text-white' : 'border-white/10 text-gray-400 hover:border-white/30'"
                    class="rounded-lg border px-4 py-3 text-left transition-colors">
                    <span class="block text-sm font-bold">60K</span>
                    <span class="block text-xs opacity-60">4,200m vert</span>
                </button>
            </div>
            <p class="text-gray-600 text-xs mt-1.5">Choose carefully. Your program is locked to this distance.</p>
        </div>

        <button type="submit" :disabled="submitting"
            class="w-full py-3 px-8 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg bg-gradient-to-r from-blue-600 to-red-600 text-white hover:from-blue-700 hover:to-red-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            <span x-text="submitting ? 'Setting up your program…' : 'Start Training Free'"></span>
        </button>

        <p class="text-gray-600 text-xs text-center">By signing up you agree to receive training tips, race updates, and occasional promotional emails from RiCON and Edify Endurance. Unsubscribe anytime.</p>
    </form>

    {{-- Re-send link --}}
    <div x-show="done !== 'existing'" class="mt-6 pt-5 border-t border-white/10">
        <button type="button" @click="resendOpen = !resendOpen"
            class="text-sm text-gray-400 hover:text-white transition-colors font-medium">
            Already signed up? Re-send my link
        </button>
        <div x-show="resendOpen" x-cloak class="mt-3 flex gap-2">
            <input type="email" x-model="resendEmail" placeholder="you@example.com" autocomplete="email"
                class="flex-1 rounded-lg border border-white/10 bg-white/5 text-white text-sm px-3.5 py-2.5 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            <button type="button" @click="resend"
                class="py-2.5 px-4 text-sm font-bold rounded-lg border border-white/20 text-white hover:bg-white/10 transition-colors">
                Send
            </button>
        </div>
    </div>
</div>

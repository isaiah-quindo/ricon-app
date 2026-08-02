{{--
    Who is booking the group. Deliberately separate from the participant list: a club
    coordinator may register ten runners without racing themselves, and admin needs to
    know who to contact about the payment.
--}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-sm font-semibold text-gray-800">Who is registering this group?</h2>
        <p class="text-xs text-gray-500 mt-0.5">
            We will contact this person about the payment. They do not need to be one of the runners.
        </p>
    </div>
    <div class="p-6">
        <label class="flex items-center gap-2.5 cursor-pointer mb-4">
            <input type="checkbox" x-model="organizerSameAsFirst" @change="syncOrganizer()"
                class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
            <span class="text-sm text-gray-700">I am also participant 1</span>
        </label>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="organizer[name]" x-model="organizer.name"
                    :readonly="organizerSameAsFirst" autocomplete="off"
                    class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent read-only:bg-gray-50 read-only:text-gray-500"
                    :class="organizerError('name') ? 'border-red-400' : 'border-gray-200'" />
                <p x-show="organizerError('name')" x-cloak class="text-xs text-red-500 mt-1" x-text="organizerError('name')"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="organizer[email]" x-model="organizer.email"
                    :readonly="organizerSameAsFirst" autocomplete="off"
                    class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent read-only:bg-gray-50 read-only:text-gray-500"
                    :class="organizerError('email') ? 'border-red-400' : 'border-gray-200'" />
                <p x-show="organizerError('email')" x-cloak class="text-xs text-red-500 mt-1" x-text="organizerError('email')"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Mobile Number <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="organizer[mobile]" x-model="organizer.mobile"
                    :readonly="organizerSameAsFirst" placeholder="+63 9XX XXX XXXX" autocomplete="off"
                    class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent read-only:bg-gray-50 read-only:text-gray-500"
                    :class="organizerError('mobile') ? 'border-red-400' : 'border-gray-200'" />
                <p x-show="organizerError('mobile')" x-cloak class="text-xs text-red-500 mt-1" x-text="organizerError('mobile')"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Team / Club <span class="text-gray-400 text-xs font-normal">(optional)</span>
                </label>
                <input type="text" name="organizer[team]" x-model="organizer.team"
                    placeholder="e.g. Don't Stop Running Club" autocomplete="off"
                    class="w-full rounded-lg border border-gray-200 text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
            </div>
        </div>
    </div>
</div>

{{-- Liability Waiver Modal --}}
<div
    x-show="showWaiver"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4"
    @keydown.escape.window="showWaiver = false">
    <div
        class="absolute inset-0 bg-black/70 backdrop-blur-sm"
        @click="showWaiver = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <div
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] sm:max-h-[90vh] flex flex-col"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">

        <div class="flex items-center justify-between px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-100 flex-shrink-0">
            <div>
                <h2 class="text-base font-bold text-gray-900">Liability Waiver &amp; Release of Claims</h2>
                <p class="text-xs text-gray-500 mt-0.5">The Great Cordillera 100 Ultra Trail</p>
            </div>
            <button type="button" @click="showWaiver = false"
                class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100"
                aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto px-4 py-4 sm:px-6 sm:py-5 space-y-5 text-sm text-gray-700 leading-relaxed flex-1">

            <p>
                This Liability Waiver and Release of Claims ("<strong>Waiver</strong>") is entered into by the undersigned participant ("<strong>Participant</strong>") in connection with their voluntary participation in <strong>The Great Cordillera 100</strong> ("<strong>the Event</strong>"), organized by RiCON ("<strong>the Organizer</strong>").
            </p>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">1. Acknowledgment of Risks</h3>
                <p>
                    The Participant acknowledges that ultra trail running is an extreme endurance sport involving inherent and significant risks, including but not limited to:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600">
                    <li>Extreme physical exertion, fatigue, and dehydration</li>
                    <li>Technical and remote mountain terrain in the Cordillera region</li>
                    <li>Severe and rapidly changing weather conditions, including rain, fog, lightning, and low temperatures</li>
                    <li>High-altitude exposure and associated altitude sickness</li>
                    <li>Falls, slips, and contact with natural hazards (rocks, roots, cliffs, river crossings)</li>
                    <li>Wildlife encounters and exposure to natural elements</li>
                    <li>Cardiac events, muscle injuries, bone fractures, and other medical emergencies</li>
                    <li>Limited or delayed access to emergency medical services in remote sections</li>
                </ul>
                <p class="mt-2">
                    The Participant confirms that they have read, understood, and fully accept these risks before registering.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">2. Assumption of Risk</h3>
                <p>
                    The Participant voluntarily and knowingly assumes all risks, known and unknown, associated with participation in the Event, including those arising from the negligence of the Organizer, its officers, volunteers, sponsors, race officials, and any other affiliated parties. The Participant agrees that their participation is entirely at their own risk.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">3. Release of Liability</h3>
                <p>
                    In consideration of being permitted to participate in the Event, the Participant, on behalf of themselves and their heirs, executors, personal representatives, and assigns, hereby <strong>releases, waives, discharges, and covenants not to sue</strong> the Organizer, its directors, officers, employees, volunteers, sponsors, land owners, race marshals, medical personnel, and all affiliated parties from any and all claims, demands, losses, liabilities, or causes of action arising out of or related to any loss, damage, injury, or death that may be sustained during or in connection with participation in the Event, whether caused by the negligence of any of the released parties or otherwise.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">4. Medical Fitness &amp; Health Declaration</h3>
                <p>
                    The Participant represents and warrants that they are physically fit and have not been advised by a licensed physician against participating in strenuous athletic events such as ultra trail running. The Participant accepts full responsibility for their own health and wellbeing before, during, and after the Event, and agrees to seek their own medical clearance as needed.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">5. Emergency Medical Authorization</h3>
                <p>
                    In the event of an emergency, the Participant authorizes the Organizer and its designated medical personnel to seek, provide, and/or arrange for emergency medical treatment at the Participant's expense. The Organizer shall not be held liable for any medical decisions made in good faith on the Participant's behalf during an emergency.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">6. Race Rules &amp; Officials' Authority</h3>
                <p>
                    The Participant agrees to comply with all race rules, safety protocols, course markings, and decisions made by race officials. Race officials reserve the right to pull a Participant from the course at any time for safety, medical, or disciplinary reasons. Such a decision shall be final and is not subject to appeal. The Participant's registration fee is non-refundable in such circumstances.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">7. Environmental Responsibility</h3>
                <p>
                    The Participant agrees to respect the natural environment of the Cordillera mountain range. This includes following a strict "leave no trace" policy: no littering, no picking of plants, and no disturbance of wildlife. Violations may result in immediate disqualification and may be reported to local authorities.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">8. Severability &amp; Governing Law</h3>
                <p>
                    If any provision of this Waiver is found to be unenforceable, the remaining provisions shall continue in full force and effect. This Waiver shall be governed by the laws of the Republic of the Philippines. Any disputes arising from this Waiver shall be submitted to the exclusive jurisdiction of the courts of the Philippines.
                </p>
            </div>

            <p class="text-xs text-gray-500 border-t border-gray-100 pt-4">
                By checking the agreement box and submitting this registration, you confirm that each participant listed has read, understood, and voluntarily agrees to all terms of this Liability Waiver. You further confirm that each participant is of legal age (18 years or older), or that a parent/guardian has consented on their behalf.
            </p>

        </div>

        <div class="px-4 py-3 sm:px-6 sm:py-4 border-t border-gray-100 flex-shrink-0 flex items-center justify-end gap-3">
            <button type="button" @click="showWaiver = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                Close
            </button>
            <button type="button" @click="showWaiver = false"
                class="px-4 py-2 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors">
                I Have Read &amp; Understood
            </button>
        </div>
    </div>
</div>

{{--
    One participant's fields, rendered inside `<template x-for="(p, i) in participants">`.

    $collapsible — group registration collapses cards to keep a long list scannable.
                   Individual registration has a single card that stays open.
--}}
@php($collapsible = $collapsible ?? true)

<div data-participant-card
    class="bg-white rounded-xl border overflow-hidden transition-colors"
    :class="p?._open ? 'border-orange-300 shadow-sm' : 'border-gray-200'">

    {{-- Card header --}}
    <div class="px-4 sm:px-6 py-4 flex items-center gap-3 {{ $collapsible ? 'cursor-pointer' : '' }}"
        :class="p?._open ? 'border-b border-gray-100 bg-orange-50/50' : 'hover:bg-gray-50'"
        @if($collapsible) @click="toggleCard(i)" @endif>

        <span class="w-7 h-7 rounded-full text-xs font-bold flex items-center justify-center flex-shrink-0"
            :class="isComplete(i) ? 'bg-green-600 text-white' : 'bg-orange-600 text-white'">
            <template x-if="isComplete(i) && !p?._open">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </template>
            <template x-if="!(isComplete(i) && !p?._open)">
                <span x-text="i + 1"></span>
            </template>
        </span>

        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-gray-900 truncate" x-text="participantLabel(i)"></p>
            <p class="text-xs text-gray-500 truncate" x-text="participantSubLabel(i)"></p>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            <span x-show="!isComplete(i)" x-cloak
                class="w-2 h-2 rounded-full bg-amber-400" title="Incomplete"></span>
            <span x-show="p?.race_category_id" x-cloak
                class="text-sm font-bold text-gray-900 hidden sm:inline"
                x-text="formatPHP(priceFor(i))"></span>

            {{-- Removing is only offered while the party stays above its minimum. --}}
            <button type="button" @click.stop="removeParticipant(i)"
                x-show="canRemove" x-cloak
                class="text-gray-300 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50"
                :aria-label="'Remove participant ' + (i + 1)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            @if($collapsible)
            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                :class="p?._open ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            @endif
        </div>
    </div>

    {{-- Card body --}}
    <div x-show="p?._open" x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0">
        <div class="p-4 sm:p-6 space-y-6">

            {{-- Personal information --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="p.first_name"
                            :name="'participants[' + i + '][first_name]'"
                            autocomplete="off"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            :class="fieldError(i, 'first_name') ? 'border-red-400' : 'border-gray-200'" />
                        <p x-show="fieldError(i, 'first_name')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'first_name')"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="p.last_name"
                            :name="'participants[' + i + '][last_name]'"
                            autocomplete="off"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            :class="fieldError(i, 'last_name') ? 'border-red-400' : 'border-gray-200'" />
                        <p x-show="fieldError(i, 'last_name')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'last_name')"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Sex <span class="text-red-500">*</span>
                        </label>
                        <select x-model="p.sex" :name="'participants[' + i + '][sex]'"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white"
                            :class="fieldError(i, 'sex') ? 'border-red-400' : 'border-gray-200'">
                            <option value="">Select sex</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <p x-show="fieldError(i, 'sex')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'sex')"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Birthdate <span class="text-red-500">*</span>
                        </label>
                        <input type="date" x-model="p.birthdate"
                            :name="'participants[' + i + '][birthdate]'"
                            :max="maxBirthdate"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            :class="fieldError(i, 'birthdate') ? 'border-red-400' : 'border-gray-200'" />
                        <p x-show="fieldError(i, 'birthdate')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'birthdate')"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" x-model="p.email"
                            :name="'participants[' + i + '][email]'"
                            autocomplete="off"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            :class="fieldError(i, 'email') ? 'border-red-400' : 'border-gray-200'" />
                        <p class="text-xs text-gray-400 mt-1" x-show="participants.length > 1" x-cloak>
                            Their confirmation email goes here.
                        </p>
                        <p x-show="fieldError(i, 'email')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'email')"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Mobile Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" x-model="p.mobile_number"
                            :name="'participants[' + i + '][mobile_number]'"
                            placeholder="+63 9XX XXX XXXX" autocomplete="off"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            :class="fieldError(i, 'mobile_number') ? 'border-red-400' : 'border-gray-200'" />
                        <p x-show="fieldError(i, 'mobile_number')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'mobile_number')"></p>
                    </div>

                    {{-- Nationality: Alpine combobox. A Preline hs-select cannot be used --}}
                    {{-- here because cards are added and removed after page load. --}}
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nationality <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" x-model="p.nationality"
                                :name="'participants[' + i + '][nationality]'"
                                @focus="open = true" @input="open = true"
                                @keydown.escape="open = false"
                                placeholder="Search nationality..."
                                autocomplete="off" role="combobox" :aria-expanded="open"
                                class="w-full rounded-lg border text-sm px-3.5 py-2.5 pe-9 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                :class="fieldError(i, 'nationality') ? 'border-red-400' : 'border-gray-200'" />
                            <div class="absolute top-1/2 end-3 -translate-y-1/2 pointer-events-none">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m7 15 5 5 5-5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m7 9 5-5 5 5" />
                                </svg>
                            </div>
                        </div>
                        <ul x-show="open" x-cloak
                            class="absolute z-30 mt-1 w-full max-h-56 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg p-1 space-y-0.5">
                            <template x-for="n in filterNationalities(p.nationality)" :key="n">
                                <li @click="p.nationality = n; open = false"
                                    class="py-2 px-3 text-sm text-gray-800 cursor-pointer hover:bg-orange-50 hover:text-orange-700 rounded-md"
                                    x-text="n"></li>
                            </template>
                            <li x-show="filterNationalities(p?.nationality).length === 0"
                                class="py-2 px-3 text-sm text-gray-400">No match. You can type it in.</li>
                        </ul>
                        <p x-show="fieldError(i, 'nationality')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'nationality')"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Team / Affiliation
                        </label>
                        <input type="text" x-model="p.affiliation"
                            :name="'participants[' + i + '][affiliation]'"
                            placeholder="e.g. Don't Stop Running Club" autocomplete="off"
                            class="w-full rounded-lg border border-gray-200 text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                        <div class="flex justify-end mt-1.5">
                            <button type="button" x-show="participants.length > 1" x-cloak
                                @click="copyAffiliationToAll(i)"
                                class="text-xs text-orange-600 hover:text-orange-700 font-medium">
                                Apply to everyone
                            </button>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Home Address <span class="text-red-500">*</span>
                            <span class="block text-xs font-normal text-gray-400">House No./Street, Barangay, City/Municipality, Province, Country</span>
                        </label>
                        <textarea rows="2" x-model="p.address"
                            :name="'participants[' + i + '][address]'"
                            maxlength="255"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"
                            :class="fieldError(i, 'address') ? 'border-red-400' : 'border-gray-200'"></textarea>
                        <div class="flex items-center justify-between mt-1">
                            <p x-show="fieldError(i, 'address')" x-cloak class="text-xs text-red-500" x-text="fieldError(i, 'address')"></p>
                            <button type="button" x-show="participants.length > 1" x-cloak
                                @click="copyAddressToAll(i)"
                                class="text-xs text-orange-600 hover:text-orange-700 font-medium ms-auto">
                                Apply to everyone
                            </button>
                        </div>
                    </div>

                    {{-- Shirt size --}}
                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-gray-700">
                                Shirt Size <span class="text-red-500">*</span>
                            </label>
                            <button type="button" @click.prevent="sizeGuideOpen = true"
                                class="text-xs text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.3 8.7 8.7 21.3a1 1 0 0 1-1.4 0l-4.6-4.6a1 1 0 0 1 0-1.4L15.3 2.7a1 1 0 0 1 1.4 0l4.6 4.6a1 1 0 0 1 0 1.4Z" />
                                    <path stroke-linecap="round" d="m7.5 10.5 2 2M10.5 7.5l2 2M13.5 4.5l2 2" />
                                </svg>
                                View size chart
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['XS', 'S', 'M', 'L', 'XL', '2XL'] as $size)
                            <label class="cursor-pointer">
                                <input type="radio" value="{{ $size }}"
                                    x-model="p.shirt_size"
                                    :name="'participants[' + i + '][shirt_size]'"
                                    class="sr-only peer">
                                <span class="inline-flex items-center justify-center w-12 h-10 border-2 rounded-lg text-sm font-semibold transition-all
                                         peer-checked:border-orange-600 peer-checked:bg-orange-600 peer-checked:text-white
                                         border-gray-200 text-gray-600 hover:border-gray-300">
                                    {{ $size }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <p x-show="fieldError(i, 'shirt_size')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'shirt_size')"></p>
                    </div>
                </div>
            </div>

            {{-- Race category --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Race Category</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($categories as $cat)
                    <label class="relative cursor-pointer">
                        <input type="radio" value="{{ $cat->id }}"
                            x-model="p.race_category_id"
                            :name="'participants[' + i + '][race_category_id]'"
                            class="sr-only peer">
                        <div class="border-2 rounded-xl p-4 transition-all h-full
                                peer-checked:border-orange-600 peer-checked:bg-orange-50
                                border-gray-200 hover:border-gray-300">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <span class="text-base font-bold text-gray-900">{{ $cat->name }}</span>
                                <span class="text-base font-bold text-orange-600 flex-shrink-0 pe-5">₱{{ number_format($cat->price, 0) }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 rounded px-2 py-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    {{ $cat->distance_km }} km
                                </span>
                                @if($cat->elevation_m)
                                <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 rounded px-2 py-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7M12 3v18" />
                                    </svg>
                                    {{ $cat->elevation_m }}m elev.
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-orange-600 peer-checked:bg-orange-600 items-center justify-center transition-all hidden peer-checked:flex">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </label>
                    @endforeach
                </div>
                <p x-show="fieldError(i, 'race_category_id')" x-cloak class="text-xs text-red-500 mt-2" x-text="fieldError(i, 'race_category_id')"></p>
            </div>

            {{-- Emergency contact --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Emergency Contact</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Contact Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="p.emergency_contact_name"
                            :name="'participants[' + i + '][emergency_contact_name]'"
                            autocomplete="off"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            :class="fieldError(i, 'emergency_contact_name') ? 'border-red-400' : 'border-gray-200'" />
                        <p x-show="fieldError(i, 'emergency_contact_name')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'emergency_contact_name')"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Contact Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" x-model="p.emergency_contact_number"
                            :name="'participants[' + i + '][emergency_contact_number]'"
                            placeholder="+63 9XX XXX XXXX" autocomplete="off"
                            class="w-full rounded-lg border text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            :class="fieldError(i, 'emergency_contact_number') ? 'border-red-400' : 'border-gray-200'" />
                        <p x-show="fieldError(i, 'emergency_contact_number')" x-cloak class="text-xs text-red-500 mt-1" x-text="fieldError(i, 'emergency_contact_number')"></p>
                    </div>
                </div>
                {{-- Teams and families usually share one; copies both fields at once. --}}
                <div class="flex justify-end mt-2">
                    <button type="button" x-show="participants.length > 1" x-cloak
                        @click="copyEmergencyContactToAll(i)"
                        class="text-xs text-orange-600 hover:text-orange-700 font-medium">
                        Apply to everyone
                    </button>
                </div>
            </div>

            @if($collapsible)
            <div class="border-t border-gray-100 pt-4 flex justify-end">
                <button type="button" @click="p._open = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Collapse
                </button>
            </div>
            @endif

        </div>
    </div>
</div>

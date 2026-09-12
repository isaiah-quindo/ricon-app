@extends('layouts.public')
@section('title', 'Volunteer')
@section('og_title', 'Become a RiCON Volunteer — RICON')
@section('og_description', "Every race is powered by more than the runners. Join RiCON's volunteer team for aid stations, course marshaling, race operations, and more.")

@section('content')

{{-- ========================================================
         HERO
    ======================================================== --}}
<section id="overview" class="relative overflow-hidden pt-16">
    <div class="absolute inset-0 select-none" style="background-color:#0d0d0d; background-image:
        radial-gradient(ellipse 75% 110% at 0% 55%, rgba(234,88,12,0.35), transparent 62%),
        radial-gradient(ellipse 55% 65% at 100% 12%, rgba(249,115,22,0.25), transparent 58%),
        radial-gradient(ellipse 65% 80% at 98% 88%, rgba(194,65,12,0.25), transparent 60%);"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

    <div class="relative z-10 mx-auto px-8 py-16 lg:py-24" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-3">Be Part of the Journey</p>
        <h1 class="text-5xl md:text-6xl xl:text-7xl font-black text-white leading-none mb-5">
            Become a RiCON <span class="text-orange-500">Volunteer</span>
        </h1>
        <div class="w-40 h-1 bg-orange-500 rounded-full mb-6"></div>

        <p class="text-gray-200 text-2xl md:text-3xl font-bold italic max-w-2xl mb-2 leading-snug" style="font-family: 'Kufam', sans-serif;">"It takes a village to bring a runner to a finish line."</p>
        <p class="text-gray-400 text-lg md:text-xl font-extrabold mb-8">Be a villager. Become a <span class="text-orange-500">RiCON</span> Volunteer.</p>

        <p class="text-gray-300 text-lg max-w-2xl mb-3 leading-relaxed">Every race is powered by more than the runners. It takes a community of people working behind the scenes to create an experience that runners will remember.</p>
        <p class="text-gray-300 text-lg max-w-2xl mb-12 leading-relaxed">At RiCON, volunteers are an essential part of every event. From aid stations and course marshaling to race operations and photographers, our volunteers help keep the race moving and make every runner's journey safer, smoother, and more memorable.</p>

        <div class="flex flex-wrap gap-4 mb-14">
            <a href="#apply" class="inline-flex items-center gap-x-2 py-3 px-6 text-lg font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">Apply to Volunteer</a>
            <a href="#roles" class="inline-flex items-center gap-x-2 py-3 px-6 text-lg font-bold rounded-lg border border-white/20 text-white hover:bg-white/10 transition-colors">See Volunteer Roles</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 border-t border-white/10 divide-y sm:divide-y-0 sm:divide-x divide-white/10">
            <div class="pt-6 sm:pr-8">
                <p class="text-gray-500 text-sm uppercase tracking-wider font-semibold mb-1">Volunteer Roles</p>
                <p class="text-white font-black text-2xl">19+</p>
            </div>
            <div class="pt-6 sm:px-8">
                <p class="text-gray-500 text-sm uppercase tracking-wider font-semibold mb-1">Min. Shift for Benefits</p>
                <p class="text-white font-black text-2xl">3 Hrs</p>
            </div>
            <div class="pt-6 sm:pl-8">
                <p class="text-gray-500 text-sm uppercase tracking-wider font-semibold mb-1">Group Bonus Threshold</p>
                <p class="text-white font-black text-2xl">90 Hrs</p>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
         QUICK NAV
    ======================================================== --}}
<div class="bg-[#111111] border-b border-white/10 sticky top-16 z-40">
    <div class="mx-auto px-8 overflow-x-auto" style="max-width:1280px;">
        <div class="flex items-center gap-6 h-12 text-lg whitespace-nowrap">
            <a href="#overview" class="text-gray-400 hover:text-white transition-colors">Overview</a>
            <a href="#roles" class="text-gray-400 hover:text-white transition-colors">Roles</a>
            <a href="#expect" class="text-gray-400 hover:text-white transition-colors">What We Look For</a>
            <a href="#apply" class="text-gray-400 hover:text-white transition-colors">Apply</a>
            <a href="#benefits" class="text-gray-400 hover:text-white transition-colors">Benefits</a>
            <a href="#groups" class="text-gray-400 hover:text-white transition-colors">Groups</a>
        </div>
    </div>
</div>

{{-- ========================================================
         VOLUNTEER ROLES
    ======================================================== --}}
@php
$roleCategories = [
    'course'  => 'Course & Trail',
    'ops'     => 'Operations & Logistics',
    'medical' => 'Medical & Safety',
    'media'   => 'Media & Comms',
    'finish'  => 'Finish Line',
];

$roles = [
    ['name' => 'Aid Station Crew', 'cat' => 'course', 'desc' => 'Prepare and manage aid stations, assist runners, and help maintain station operations.'],
    ['name' => 'Trail Workers', 'cat' => 'course', 'desc' => 'Prepare, maintain, and improve race trails before, during, and after the event, including clearing footing and helping with trail development.'],
    ['name' => 'Course Marshal', 'cat' => 'course', 'desc' => 'Guide runners, monitor course sections, and help ensure runners stay on the designated route.'],
    ['name' => 'Sweepers', 'cat' => 'course', 'desc' => 'Stay at the back of the field to monitor the last runners, ensure no one is left behind, and report course conditions and hazards.'],
    ['name' => 'Forerunner', 'cat' => 'course', 'desc' => 'Checks and prepares the course before runners arrive, verifying course markings and hazards, and reporting issues to the organizer.'],
    ['name' => 'Course Tagging', 'cat' => 'course', 'desc' => 'Ribbon-tagging, reflectors, ground flags, arrow signs, and caution tape along the route.'],
    ['name' => 'Race Base Operations', 'cat' => 'ops', 'desc' => 'Assist with registration, gear check, baggage counter, logistics, and general race-day activities.'],
    ['name' => 'Drop-Bag Coordinator', 'cat' => 'ops', 'desc' => "Help manage and transport runners' drop bags to and from designated aid stations."],
    ['name' => 'Logistics & Setup/Teardown Crew', 'cat' => 'ops', 'desc' => 'Help with event setup, equipment movement, signage, barriers, and venue preparation.'],
    ['name' => 'Traffic & Parking Marshal', 'cat' => 'ops', 'desc' => 'Manage vehicle movement around the race venue and aid stations, directing traffic and keeping access routes clear.'],
    ['name' => 'Driver', 'cat' => 'ops', 'desc' => 'Transport runners, staff, and supplies between key locations, and assist with runner withdrawals.'],
    ['name' => 'Other Event Roles', 'cat' => 'ops', 'desc' => 'Additional opportunities may be available depending on the needs of each race.'],
    ['name' => 'Medical Doctor', 'cat' => 'medical', 'desc' => 'Provide on-site medical care, assessing conditions like dehydration, injuries, and fatigue, and making treatment or withdrawal decisions.'],
    ['name' => 'First Aid Responder', 'cat' => 'medical', 'desc' => 'Deliver immediate care for minor injuries, stabilize runners, and escalate serious cases to medical teams.'],
    ['name' => 'Safety & Medical Support', 'cat' => 'medical', 'desc' => 'Assist designated safety and medical teams within your assigned responsibilities.'],
    ['name' => 'Base Camp Communications Team', 'cat' => 'media', 'desc' => 'Support race communications, progress tracking, radio coordination, and announcements throughout race day.'],
    ['name' => 'Photographers', 'cat' => 'media', 'desc' => 'Help capture runners, stories, and the atmosphere of the race.'],
    ['name' => 'Emcee/Host', 'cat' => 'media', 'desc' => 'Lead event communications at the start/finish area, guiding the program and keeping runners and spectators informed.'],
    ['name' => 'Finish Line Team', 'cat' => 'finish', 'desc' => 'Assist with runner arrivals, medals, timing coordination, and welcoming runners at the finish.'],
];
@endphp

<section id="roles" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Volunteer Opportunities</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Depending on the Event, Roles May Include</h2>
        <p class="text-gray-400 max-w-2xl mb-10 leading-relaxed">Filter by area or search for a role. Tap any card to read the full description.</p>

        <div x-data='{
                roles: @json($roles),
                categories: @json($roleCategories),
                activeCat: "all",
                search: "",
                opened: [],
                get filtered() {
                    const term = this.search.toLowerCase().trim();
                    return this.roles.filter(r =>
                        (this.activeCat === "all" || r.cat === this.activeCat) &&
                        (!term || r.name.toLowerCase().includes(term) || r.desc.toLowerCase().includes(term))
                    );
                },
                toggle(name) {
                    this.opened = this.opened.includes(name) ? this.opened.filter(n => n !== name) : [...this.opened, name];
                },
            }'>

            <div class="flex items-center gap-3 bg-[#1a1a1a] border border-white/10 rounded-lg px-4 py-3 mb-5 max-w-sm">
                <svg class="w-4 h-4 text-gray-500 flex-none" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" x-model="search" placeholder="Search roles (e.g. medical, photo, driver)" class="bg-transparent border-none outline-none text-white text-sm w-full placeholder:text-gray-500">
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <button type="button" @click="activeCat = 'all'"
                    class="text-sm font-semibold px-4 py-2 rounded-full border transition-colors"
                    :class="activeCat === 'all' ? 'bg-orange-600 border-orange-600 text-white' : 'border-white/20 text-gray-400 hover:text-white hover:border-white/40'">
                    All Roles
                </button>
                @foreach($roleCategories as $key => $label)
                <button type="button" @click="activeCat = '{{ $key }}'"
                    class="text-sm font-semibold px-4 py-2 rounded-full border transition-colors"
                    :class="activeCat === '{{ $key }}' ? 'bg-orange-600 border-orange-600 text-white' : 'border-white/20 text-gray-400 hover:text-white hover:border-white/40'">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            <p class="text-sm text-gray-500 mb-5" x-text="filtered.length + ' role' + (filtered.length === 1 ? '' : 's') + ' shown'"></p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="role in filtered" :key="role.name">
                    <div @click="toggle(role.name)" class="bg-[#111111] border border-white/10 rounded-2xl p-5 cursor-pointer hover:border-white/20 transition-colors">
                        <p class="text-orange-500 text-sm font-bold uppercase tracking-wider mb-2" x-text="categories[role.cat]"></p>
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="text-white font-bold text-lg" x-text="role.name"></h4>
                            <svg class="w-4 h-4 text-orange-500 flex-none mt-1 transition-transform" :class="opened.includes(role.name) ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <p class="text-gray-400 text-base leading-relaxed mt-2" x-show="opened.includes(role.name)" x-text="role.desc"></p>
                    </div>
                </template>
            </div>

            <p class="text-gray-500 text-sm mt-6" x-show="filtered.length === 0">No roles match your search. Try a different keyword or filter.</p>
        </div>
    </div>
</section>

{{-- ========================================================
         WHAT WE LOOK FOR
    ======================================================== --}}
<section id="expect" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Before You Apply</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">What We Look For</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">There's no single trait that decides a match. We weigh all of this together to place you where you'll do the most good, and enjoy it most.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach([
                ['icon' => 'M9 12l2 2 4-4|M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z', 'title' => 'Role Fit', 'desc' => "Your skills, experience, and ability match the role you're placed in."],
                ['icon' => 'M12 2 2 7l10 5 10-5-10-5Z|M2 17l10 5 10-5|M2 12l10 5 10-5', 'title' => 'Relevant Experience', 'desc' => "Prior experience in races, sports, events, or outdoor activities helps, but isn't required for every role."],
                ['icon' => 'M13 2 3 14h7l-1 8 10-12h-7l1-8Z', 'title' => 'Physical Readiness', 'desc' => "Capable of handling the physical requirements of your assignment, whether that's a full day on your feet or hours on the trail."],
                ['icon' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z|M12 7v5l3 3', 'title' => 'Availability', 'desc' => "Able to commit to the required shift and the event's schedule."],
                ['icon' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', 'title' => 'Communication', 'desc' => 'Can communicate clearly with runners, staff, and fellow volunteers, often under pressure.'],
                ['icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2|M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0 0', 'title' => 'Teamwork', 'desc' => 'Willing and able to work effectively as part of a team.'],
                ['icon' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z|m9 12 2 2 4-4', 'title' => 'Responsibility', 'desc' => 'Reliable, punctual, and willing to follow instructions on race day.'],
                ['icon' => 'M12 9v4M12 17h.01|M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z', 'title' => 'Problem-Solving', 'desc' => 'Stays calm and responds appropriately to unexpected situations on course.'],
                ['icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z', 'title' => 'Safety Awareness', 'desc' => 'Understands and follows safety and event protocols at all times.'],
                ['icon' => 'M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z', 'title' => 'Commitment', 'desc' => 'Genuine interest in supporting RiCON and its participants.'],
            ] as $card)
            <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl p-5 flex gap-4 items-start">
                <div class="w-10 h-10 rounded-lg bg-orange-500/10 text-orange-500 flex items-center justify-center flex-none">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        @foreach(explode('|', $card['icon']) as $path)
                        <path d="{{ $path }}"/>
                        @endforeach
                    </svg>
                </div>
                <div>
                    <h4 class="text-white font-bold text-lg mb-1">{{ $card['title'] }}</h4>
                    <p class="text-gray-400 text-base leading-relaxed">{{ $card['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         APPLY
    ======================================================== --}}
<section id="apply" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Application</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Volunteer Application</h2>
        <p class="text-gray-400 mb-10 max-w-2xl leading-relaxed">A few minutes, five short steps. All fields marked required (*) must be completed.</p>

        <div class="bg-[#111111] border border-white/10 rounded-2xl overflow-hidden">

            <div id="volCheckpointBar" class="flex border-b border-white/10 overflow-x-auto">
                @foreach(['Personal', 'Running', 'Experience', 'Skills', 'Preference'] as $i => $label)
                <div class="vol-cp flex-1 min-w-[110px] px-4 py-4 border-r border-white/10 last:border-r-0 text-sm font-semibold uppercase tracking-wide {{ $i === 0 ? 'text-orange-500' : 'text-gray-500' }}" data-cp-index="{{ $i }}">
                    <span class="vol-cp-num block font-black text-lg mb-1 {{ $i === 0 ? 'text-orange-500' : 'text-white' }}">{{ $i + 1 }}</span>{{ $label }}
                </div>
                @endforeach
            </div>

            <form id="volForm" novalidate class="p-6 md:p-9">

                {{-- Step 0: Personal Information --}}
                <div class="vol-step" data-index="0">
                    <p class="text-orange-500 text-sm font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Personal Information</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Full Name <span class="text-orange-500">*</span></label>
                            <input type="text" name="full_name" required class="vol-input">
                            <p class="vol-error">Enter your full name.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Email Address <span class="text-orange-500">*</span></label>
                            <input type="email" name="email" required class="vol-input">
                            <p class="vol-error">Enter a valid email address.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Mobile Number <span class="text-orange-500">*</span></label>
                            <input type="tel" name="mobile_number" required class="vol-input">
                            <p class="vol-error">Enter your mobile number.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">City / Province <span class="text-orange-500">*</span></label>
                            <input type="text" name="city_province" required class="vol-input">
                            <p class="vol-error">Enter your city or province.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Shirt Size <span class="text-orange-500">*</span></label>
                            <select name="shirt_size" required class="vol-input">
                                <option value="">Select one</option>
                                <option>XS</option>
                                <option>S</option>
                                <option>M</option>
                                <option>L</option>
                                <option>XL</option>
                                <option>XXL</option>
                            </select>
                            <p class="vol-error">Select a shirt size.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Preferred Hours of Service <span class="text-orange-500">*</span></label>
                            <select name="preferred_hours" required class="vol-input">
                                <option value="">Select one</option>
                                <option>3&ndash;6 Hours</option>
                                <option>6&ndash;9 Hours</option>
                                <option>9+ Hours</option>
                                <option>Flexible / Whatever is needed</option>
                            </select>
                            <p class="vol-error">Select your preferred hours of service.</p>
                        </div>
                    </div>
                    <div data-require-one data-field="available_dates">
                        <label class="block text-lg font-semibold text-white mb-1.5">Date(s) Available <span class="text-orange-500">*</span></label>
                        <p class="text-sm text-gray-500 mb-2">Select all that apply.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach(['November 13, 2026', 'November 14, 2026', 'November 15, 2026'] as $date)
                            <label class="vol-option"><input type="checkbox" value="{{ $date }}">{{ $date }}</label>
                            @endforeach
                        </div>
                        <p class="vol-error">Select at least one available date.</p>
                    </div>
                </div>

                {{-- Step 1: Running Background --}}
                <div class="vol-step hidden" data-index="1">
                    <p class="text-orange-500 text-sm font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Running Background</p>

                    <div class="mb-5" data-require-one data-field="running_experience">
                        <label class="block text-lg font-semibold text-white mb-1.5">Running Experience <span class="text-orange-500">*</span></label>
                        <p class="text-sm text-gray-500 mb-2">Select all that apply.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach(['Recreational Runner', 'Road Runner', 'Trail Runner', 'Ultra Runner', 'Running Coach', 'Running Club / Community Member', 'Not a Runner'] as $opt)
                            <label class="vol-option"><input type="checkbox" value="{{ $opt }}">{{ $opt }}</label>
                            @endforeach
                        </div>
                        <p class="vol-error">Select at least one option.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Years of Running Experience <span class="text-orange-500">*</span></label>
                            <select name="years_running" required class="vol-input">
                                <option value="">Select one</option>
                                <option>Less than 1 year</option>
                                <option>1&ndash;2 years</option>
                                <option>3&ndash;5 years</option>
                                <option>6&ndash;10 years</option>
                                <option>More than 10 years</option>
                            </select>
                            <p class="vol-error">Select an option.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Longest Trail Run Completed</label>
                            <select name="longest_trail_run" class="vol-input">
                                <option value="">Select one</option>
                                <option>Less than 10 KM</option>
                                <option>10&ndash;20 KM</option>
                                <option>21&ndash;30 KM</option>
                                <option>31&ndash;50 KM</option>
                                <option>51&ndash;80 KM</option>
                                <option>81&ndash;100 KM</option>
                                <option>More than 100 KM</option>
                                <option>No Trail Running Experience</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-white mb-1.5">Longest Distance Completed (Any Discipline)</label>
                        <select name="longest_distance" class="vol-input">
                            <option value="">Select one</option>
                            <option>5 KM</option>
                            <option>10 KM</option>
                            <option>21 KM</option>
                            <option>42 KM</option>
                            <option>50 KM</option>
                            <option>60 KM</option>
                            <option>80 KM</option>
                            <option>100 KM</option>
                            <option>More than 100 KM</option>
                        </select>
                    </div>
                </div>

                {{-- Step 2: Event & Volunteer Experience --}}
                <div class="vol-step hidden" data-index="2">
                    <p class="text-orange-500 text-sm font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Event &amp; Volunteer Experience</p>

                    <div class="mb-5" data-field="previous_event_experience">
                        <label class="block text-lg font-semibold text-white mb-1.5">Previous Race / Event Experience</label>
                        <p class="text-sm text-gray-500 mb-2">Select all that apply.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach(['Trail Running Event', 'Road Running Event', 'Cycling Event', 'Outdoor / Adventure Event', 'Other Sports Event', 'Non-Sports Event', 'No Previous Event Experience'] as $opt)
                            <label class="vol-option"><input type="checkbox" value="{{ $opt }}">{{ $opt }}</label>
                            @endforeach
                        </div>
                    </div>

                    <div data-field="previous_volunteer_roles">
                        <label class="block text-lg font-semibold text-white mb-1.5">Previous Volunteer / Event Roles</label>
                        <p class="text-sm text-gray-500 mb-2">Select all that apply.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                            @foreach(['Aid Station Crew', 'Course Marshal', 'Traffic & Parking Marshal', 'Registration / Check-in', 'Race Kit Distribution', 'Drop Bag Coordinator', 'Sweeper', 'Timing / Results', 'Medical / First Aid', 'Photography', 'Videography', 'Radio / Communications', 'Logistics', 'Team Leader / Coordinator'] as $opt)
                            <label class="vol-option"><input type="checkbox" value="{{ $opt }}">{{ $opt }}</label>
                            @endforeach
                        </div>
                        <input type="text" name="previous_roles_other" placeholder="Other (please specify)" class="vol-input">
                    </div>
                </div>

                {{-- Step 3: Skills & Readiness --}}
                <div class="vol-step hidden" data-index="3">
                    <p class="text-orange-500 text-sm font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Skills &amp; Readiness</p>

                    <div class="mb-5" data-field="skills_certifications">
                        <label class="block text-lg font-semibold text-white mb-1.5">Skills &amp; Certifications</label>
                        <p class="text-sm text-gray-500 mb-2">Select all that apply.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach(['First Aid Certified', 'CPR Certified', 'Radio Communication', 'GPS / Navigation', 'Map Reading', 'Driving', 'Motorcycle Riding', 'Hiking / Mountaineering', 'Photography', 'Videography', 'Drone Operation', 'Crowd / Traffic Management', 'Event Logistics', 'None'] as $opt)
                            <label class="vol-option"><input type="checkbox" value="{{ $opt }}">{{ $opt }}</label>
                            @endforeach
                        </div>
                    </div>

                    <div data-field="physical_readiness">
                        <label class="block text-lg font-semibold text-white mb-1.5">Physical &amp; Outdoor Readiness</label>
                        <p class="text-sm text-gray-500 mb-2">Select all that apply.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach(['Comfortable working outdoors', 'Comfortable in changing weather', 'Comfortable standing/walking long periods', 'Comfortable in remote/trail locations', 'Able to complete required shift', 'Willing to do physically demanding tasks'] as $opt)
                            <label class="vol-option"><input type="checkbox" value="{{ $opt }}">{{ $opt }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Step 4: Role Preference & Consent --}}
                <div class="vol-step hidden" data-index="4">
                    <p class="text-orange-500 text-sm font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Role Preference &amp; Consent</p>

                    <div class="mb-2">
                        <label class="block text-lg font-semibold text-white mb-1.5">Preferred Volunteer Role <span class="text-orange-500">*</span></label>
                        <select name="preferred_role" id="volPreferredRole" required class="vol-input">
                            <option value="">Select one</option>
                            <option>Aid Station Crew</option>
                            <option>Course Marshal</option>
                            <option>Traffic &amp; Parking Marshal</option>
                            <option>Drop Bag Coordinator</option>
                            <option>Registration / Check-in</option>
                            <option>Race Kit Distribution</option>
                            <option>Sweeper</option>
                            <option>Photography</option>
                            <option>Videography</option>
                            <option>Communications / Radio</option>
                            <option>Logistics</option>
                            <option>Other</option>
                        </select>
                        <p class="vol-error">Select a preferred role.</p>
                    </div>
                    <div id="volOtherRoleField" class="hidden mb-6">
                        <input type="text" name="preferred_role_other" placeholder="Please specify" class="vol-input">
                    </div>

                    <label class="flex items-start gap-3 text-lg text-gray-400 mb-4">
                        <input type="checkbox" name="consent_shift" required class="mt-1 accent-orange-500 w-4 h-4 flex-none">
                        <span>I can commit to the shift length required for my assigned role.</span>
                    </label>
                    <label class="flex items-start gap-3 text-lg text-gray-400 mb-4">
                        <input type="checkbox" name="consent_benefits" required class="mt-1 accent-orange-500 w-4 h-4 flex-none">
                        <span>I understand volunteer benefits are tied to hours served, as outlined on this page.</span>
                    </label>
                    <label class="flex items-start gap-3 text-lg text-gray-400">
                        <input type="checkbox" name="consent_alt_role" class="mt-1 accent-orange-500 w-4 h-4 flex-none">
                        <span>I am still willing to volunteer in a different role if my preferred role's slots are full.</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
                    <button type="button" id="volBtnBack" disabled
                        class="py-3 px-6 text-lg font-bold rounded-lg border border-white/20 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent transition-colors">
                        Back
                    </button>
                    <span id="volStepStatus" class="hidden sm:inline text-sm font-semibold uppercase tracking-wide text-gray-500"></span>
                    <button type="button" id="volBtnNext"
                        class="py-3 px-6 text-lg font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                        Next
                    </button>
                </div>
            </form>

            <div id="volReviewPanel" class="hidden p-9 md:p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-orange-500/10 text-orange-500 flex items-center justify-center mx-auto mb-5 text-xl font-black">&check;</div>
                <h3 class="text-white font-bold text-xl mb-2">Application Received</h3>
                <p class="text-gray-400 max-w-md mx-auto mb-6 text-lg leading-relaxed">Thanks for stepping up. We've sent a confirmation to your email, and RiCON's volunteer coordination team will reach out with next steps.</p>
                <dl id="volReviewSummary" class="text-left max-w-md mx-auto mb-6 rounded-lg border border-white/10 overflow-hidden divide-y divide-white/10"></dl>
                <p class="text-base text-gray-500 border border-dashed border-white/20 rounded-lg px-4 py-3 max-w-md mx-auto text-left">Keep an eye on your inbox, including your spam folder, for updates from RiCON.</p>
            </div>
            <p id="volSubmitError" class="hidden px-6 md:px-9 pb-6 text-lg text-orange-500"></p>
        </div>
    </div>
</section>

{{-- ========================================================
         BENEFITS
    ======================================================== --}}
<section id="benefits" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Volunteer Benefits</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">We Give Back to the People Who Show Up</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">While you're volunteering your time, we want to show our appreciation with benefits and support throughout the event. Bring your friends and be part of it.</p>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-7 items-start">
            <div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl p-6">
                        <p class="text-orange-500 font-black text-2xl mb-5">3&ndash;6 Hrs</p>
                        <ul class="space-y-2.5">
                            @foreach(['RiCON Volunteer Shirt', 'Meal During the Event', 'Sponsor Discounts and Vouchers'] as $perk)
                            <li class="flex gap-2 items-start text-gray-400 text-base"><span class="text-green-400 font-bold flex-none">&check;</span>{{ $perk }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-[#0d0d0d] border border-orange-600 rounded-2xl p-6 relative">
                        <span class="absolute -top-3 right-5 bg-orange-600 text-white text-sm font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">Popular</span>
                        <p class="text-orange-500 font-black text-2xl mb-5">6&ndash;9 Hrs</p>
                        <ul class="space-y-2.5">
                            @foreach(['RiCON Volunteer Shirt', 'Meal & Snack During the Event', 'Sponsor Discounts and Vouchers'] as $perk)
                            <li class="flex gap-2 items-start text-gray-400 text-base"><span class="text-green-400 font-bold flex-none">&check;</span>{{ $perk }}</li>
                            @endforeach
                            <li class="flex gap-2 items-start text-white font-semibold text-base"><span class="text-green-400 font-bold flex-none">&check;</span>+ &#8369;1,500 Race Credit/Voucher toward a future RiCON event</li>
                        </ul>
                    </div>

                    <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl p-6 relative md:col-span-2">
                        <span class="absolute -top-3 right-5 bg-orange-600 text-white text-sm font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">Best Value</span>
                        <p class="text-orange-500 font-black text-2xl mb-5">9+ Hrs</p>
                        <ul class="space-y-2.5">
                            @foreach(['RiCON Volunteer Shirt', 'Meal & Snack During the Event', 'Sponsor Discounts and Vouchers'] as $perk)
                            <li class="flex gap-2 items-start text-gray-400 text-base"><span class="text-green-400 font-bold flex-none">&check;</span>{{ $perk }}</li>
                            @endforeach
                            <li class="flex gap-2 items-start text-white font-semibold text-base"><span class="text-green-400 font-bold flex-none">&check;</span>+ Future Free Race Kit for a selected upcoming RiCON event</li>
                        </ul>
                    </div>
                </div>
                <p class="text-gray-500 text-base mt-6">Note: Specific sponsor discounts, race credits, and free race kits may vary depending on the event and participating partners.</p>
            </div>

            <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl p-5 lg:sticky lg:top-32"
                x-data="{
                    view: 'front',
                    images: {
                        front: '{{ asset('images/volunteer-shirt-front.png') }}',
                        back: '{{ asset('images/volunteer-shirt-back.png') }}',
                    },
                }">
                <div class="bg-[#C9C9C9] rounded-lg overflow-hidden aspect-square flex items-center justify-center">
                    <img :src="images[view]" :alt="'RiCON Volunteer Crew Shirt, ' + view + ' view'" class="w-full h-full object-contain">
                </div>
                <div class="flex gap-1.5 mt-3.5 bg-[#1a1a1a] rounded-full p-1">
                    <button type="button" @click="view = 'front'"
                        class="flex-1 text-sm font-bold py-2 rounded-full transition-colors"
                        :class="view === 'front' ? 'bg-orange-600 text-white' : 'text-gray-400'">Front</button>
                    <button type="button" @click="view = 'back'"
                        class="flex-1 text-sm font-bold py-2 rounded-full transition-colors"
                        :class="view === 'back' ? 'bg-orange-600 text-white' : 'text-gray-400'">Back</button>
                </div>
                <p class="mt-3.5 text-sm text-gray-400 text-center leading-relaxed">
                    <strong class="block text-white font-bold text-sm mb-1">RiCON Official Crew Shirt</strong>
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
         RUNNING GROUPS & COMMUNITIES
    ======================================================== --}}
<section id="groups" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Running Groups &amp; Communities</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Serve Together, Run Together</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">Running groups, clubs, and communities are encouraged to volunteer together and be part of the RiCON experience.</p>

        <div class="bg-[#111111] border border-white/10 rounded-2xl p-6 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="text-white font-bold text-xl mb-3">90 Hours = 3 Complimentary Race Kits</h3>
                <p class="text-gray-400 text-base leading-relaxed mb-3">In addition to individual volunteer benefits, groups and communities that collectively complete a minimum of 90 volunteer hours may receive 3 complimentary race kits for TGC100 or a selected upcoming RiCON event. Kits may be assigned to any member of the participating group.</p>
                <p class="text-gray-400 text-base leading-relaxed mb-3">Volunteer hours must be completed and verified by the RiCON organizing team. Complimentary race kits are valid only for TGC100 and selected upcoming RiCON events, subject to event availability and other applicable terms.</p>
                <p class="text-gray-400 text-base leading-relaxed">Organizers provide space for team banners at the race base or aid stations. All collaborating running groups get their team logo featured on the RiCON website, social media, race briefing, and media launch.</p>
            </div>

            <div class="bg-[#1a1a1a] border border-white/10 rounded-lg p-6"
                x-data="{
                    people: 10,
                    hours: 9,
                    get total() { return (parseInt(this.people) || 0) * (parseInt(this.hours) || 0); },
                    get pct() { return Math.min(100, (this.total / 90) * 100); },
                    get met() { return this.total >= 90; },
                }">
                <p class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Group Hours Calculator</p>
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm uppercase tracking-wide text-gray-500 font-semibold mb-1.5">Volunteers</label>
                        <input type="number" min="1" x-model.number="people" class="vol-input tabular-nums">
                    </div>
                    <div>
                        <label class="block text-sm uppercase tracking-wide text-gray-500 font-semibold mb-1.5">Hours Each</label>
                        <input type="number" min="1" x-model.number="hours" class="vol-input tabular-nums">
                    </div>
                </div>
                <div class="h-2 bg-white/10 rounded-full overflow-hidden mb-5">
                    <div class="h-full bg-orange-600 transition-all duration-300" :style="`width: ${pct}%`"></div>
                </div>
                <div class="flex justify-between items-center border-t border-white/10 pt-4">
                    <div>
                        <span class="block text-sm text-gray-500 mb-0.5">Total Hours</span>
                        <span class="font-black text-2xl text-white tabular-nums" x-text="total"></span>
                    </div>
                    <span class="text-sm font-bold px-3 py-1.5 rounded-full"
                        :class="met ? 'bg-green-500/15 text-green-400' : 'bg-orange-500/10 text-orange-500'"
                        x-text="met ? 'Threshold Met' : (90 - total) + ' Hrs to Go'"></span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .vol-input {
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
        border-radius: 0.5rem;
        border: 1px solid rgba(255,255,255,0.1);
        background: #1a1a1a;
        color: #fff;
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    .vol-input:focus { outline: none; border-color: #f97316; }
    .vol-input::placeholder { color: #6b7280; }
    .vol-option {
        display: flex; align-items: center; gap: 0.625rem;
        background: #1a1a1a; border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.5rem; padding: 0.625rem 0.75rem;
        font-size: 1rem; color: #9ca3af; cursor: pointer;
    }
    .vol-option input { accent-color: #f97316; flex: none; }
    .vol-option:has(input:checked) { border-color: #f97316; color: #fff; background: rgba(249,115,22,0.1); }
    .vol-error { display: none; color: #f97316; font-size: 1rem; margin-top: 0.375rem; }
    .vol-field-invalid .vol-error { display: block; }
    .vol-field-invalid .vol-option { border-color: #f97316; }
</style>

<script>
(function () {
    // ---- preferred role "other" reveal ----
    var prefSelect = document.getElementById('volPreferredRole');
    var otherRoleField = document.getElementById('volOtherRoleField');
    prefSelect.addEventListener('change', function () {
        otherRoleField.classList.toggle('hidden', prefSelect.value !== 'Other');
    });

    // ---- multi-step form ----
    var steps = Array.prototype.slice.call(document.querySelectorAll('#volForm .vol-step'));
    var checkpoints = Array.prototype.slice.call(document.querySelectorAll('#volCheckpointBar .vol-cp'));
    var current = 0;
    var btnBack = document.getElementById('volBtnBack');
    var btnNext = document.getElementById('volBtnNext');
    var stepStatus = document.getElementById('volStepStatus');
    var form = document.getElementById('volForm');
    var reviewPanel = document.getElementById('volReviewPanel');

    function renderStep() {
        steps.forEach(function (s, i) { s.classList.toggle('hidden', i !== current); });
        checkpoints.forEach(function (cp, i) {
            var isCurrent = i === current;
            var isDone = i < current;
            cp.classList.toggle('text-orange-500', isCurrent);
            cp.classList.toggle('text-gray-500', !isCurrent && !isDone);
            cp.classList.toggle('text-green-500', isDone);
            var num = cp.querySelector('.vol-cp-num');
            num.classList.toggle('text-orange-500', isCurrent);
            num.classList.toggle('text-white', !isCurrent);
        });
        btnBack.disabled = current === 0;
        btnNext.textContent = current === steps.length - 1 ? 'Submit Application' : 'Next';
        stepStatus.textContent = 'Checkpoint ' + (current + 1) + ' of ' + steps.length;
    }

    function validateStep(index) {
        var ok = true;
        var firstInvalid = null;
        var step = steps[index];

        Array.prototype.forEach.call(step.querySelectorAll('[required]'), function (field) {
            var wrapper = field.closest('div') || field.parentElement;
            var isCheckbox = field.type === 'checkbox';
            var valid = isCheckbox ? field.checked : field.value.trim().length > 0;
            if (wrapper) wrapper.classList.toggle('vol-field-invalid', !valid);
            if (!valid) { ok = false; if (!firstInvalid) firstInvalid = field; }
        });

        Array.prototype.forEach.call(step.querySelectorAll('[data-require-one]'), function (group) {
            var anyChecked = Array.prototype.some.call(group.querySelectorAll('input[type="checkbox"]'), function (cb) { return cb.checked; });
            group.classList.toggle('vol-field-invalid', !anyChecked);
            if (!anyChecked) { ok = false; if (!firstInvalid) firstInvalid = group; }
        });

        if (firstInvalid && firstInvalid.focus) firstInvalid.focus();
        return ok;
    }

    btnNext.addEventListener('click', function () {
        if (!validateStep(current)) return;
        if (current < steps.length - 1) { current++; renderStep(); }
        else { submitApplication(); }
    });
    btnBack.addEventListener('click', function () { if (current > 0) { current--; renderStep(); } });

    var submitError = document.getElementById('volSubmitError');

    function showSummary() {
        var summary = document.getElementById('volReviewSummary');
        summary.innerHTML = '';
        var preferredRole = form.querySelector('[name="preferred_role"]').value;
        if (preferredRole === 'Other') {
            var other = form.querySelector('[name="preferred_role_other"]').value;
            if (other) preferredRole = other;
        }
        var rows = [
            ['Applicant', form.querySelector('[name="full_name"]').value],
            ['Email', form.querySelector('[name="email"]').value],
            ['Preferred Role', preferredRole],
            ['City / Province', form.querySelector('[name="city_province"]').value],
        ];
        rows.forEach(function (r) {
            var row = document.createElement('div');
            row.className = 'flex justify-between gap-4 px-4 py-2.5 text-lg bg-[#1a1a1a]';
            row.innerHTML = '<dt class="text-gray-500">' + r[0] + '</dt><dd class="font-semibold text-white text-right">' + (r[1] || 'Not provided') + '</dd>';
            summary.appendChild(row);
        });
    }

    function collectPayload() {
        var payload = {};
        Array.prototype.forEach.call(form.elements, function (el) {
            if (!el.name) return;
            payload[el.name] = el.type === 'checkbox' ? el.checked : el.value;
        });
        Array.prototype.forEach.call(form.querySelectorAll('[data-field]'), function (group) {
            payload[group.dataset.field] = Array.prototype.filter.call(group.querySelectorAll('input[type="checkbox"]'), function (cb) {
                return cb.checked;
            }).map(function (cb) { return cb.value; });
        });
        return payload;
    }

    function submitApplication() {
        submitError.classList.add('hidden');
        submitError.textContent = '';
        btnNext.disabled = true;
        btnBack.disabled = true;
        var originalLabel = btnNext.textContent;
        btnNext.textContent = 'Submitting…';

        fetch("{{ route('programs.volunteer.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    ?? "{{ csrf_token() }}",
            },
            body: JSON.stringify(collectPayload()),
        })
        .then(function (res) {
            if (res.status === 429) {
                submitError.textContent = 'Too many attempts. Please wait a minute and try again.';
                submitError.classList.remove('hidden');
                return null;
            }
            if (res.status === 422) {
                return res.json().then(function (json) {
                    var message = json.errors ? Object.values(json.errors)[0][0] : 'Please check your entries and try again.';
                    submitError.textContent = message;
                    submitError.classList.remove('hidden');
                    return null;
                });
            }
            if (!res.ok) {
                submitError.textContent = 'Something went wrong. Please try again.';
                submitError.classList.remove('hidden');
                return null;
            }
            return res.json();
        })
        .then(function (json) {
            if (!json) return;
            showSummary();
            form.classList.add('hidden');
            reviewPanel.classList.remove('hidden');
        })
        .catch(function () {
            submitError.textContent = 'Something went wrong. Please check your connection and try again.';
            submitError.classList.remove('hidden');
        })
        .finally(function () {
            btnNext.disabled = false;
            btnBack.disabled = current === 0;
            btnNext.textContent = originalLabel;
        });
    }

    renderStep();
})();
</script>

@endsection

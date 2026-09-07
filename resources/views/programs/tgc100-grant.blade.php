@extends('layouts.public')
@section('title', 'TGC100 Grant')
@section('og_title', 'TGC100 Grant — RICON')
@section('og_description', "The TGC100 Grant is RiCON's commitment to supporting one runner taking on The Great Cordillera 100KM. Registration, accommodation, and race-week allowances, covered.")

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
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-3">Applications Open · September 7-13</p>
        <h1 class="text-5xl md:text-6xl xl:text-7xl font-black text-white leading-none mb-5">
            TGC<span class="text-orange-500">100</span> Grant
        </h1>
        <div class="w-40 h-1 bg-orange-500 rounded-full mb-6"></div>
        <p class="text-gray-300 text-lg max-w-2xl mb-3 leading-relaxed">The TGC100 Grant is RiCON's commitment to supporting one runner taking on The Great Cordillera 100KM.</p>
        <p class="text-gray-300 text-lg max-w-2xl mb-3 leading-relaxed">We cover race registration, accommodation, and selected race-week expenses. You bring the training, the commitment, and the reason you're willing to take on 100 kilometers.</p>
        <p class="text-gray-500 text-lg max-w-2xl mb-12">More than a sponsorship, this is our way of investing in the people and stories that move Philippine trail running forward.</p>

        <div class="flex flex-wrap gap-10 mb-14">
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold mb-1">Race Date</p>
                <p class="text-white font-black text-2xl">Nov 13, 2026</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold mb-1">Distance</p>
                <p class="text-white font-black text-2xl">100<span class="text-base text-gray-400 font-bold ml-1">KM</span></p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold mb-1">Elevation Gain</p>
                <p class="text-white font-black text-2xl">7,000<span class="text-base text-gray-400 font-bold ml-1">M D+</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 border-t border-white/10 divide-y sm:divide-y-0 sm:divide-x divide-white/10">
            <div class="pt-6 sm:pr-8">
                <p class="text-orange-500 text-[11px] font-bold uppercase tracking-wider mb-2">Applications</p>
                <p class="text-white font-bold">September 7-13</p>
            </div>
            <div class="pt-6 sm:px-8">
                <p class="text-orange-500 text-[11px] font-bold uppercase tracking-wider mb-2">Interview Invite</p>
                <p class="text-white font-bold">2-3 Business Days After Applying</p>
            </div>
            <div class="pt-6 sm:pl-8">
                <p class="text-orange-500 text-[11px] font-bold uppercase tracking-wider mb-2">Grantee Announced</p>
                <p class="text-white font-bold">September 18</p>
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
            <a href="#coverage" class="text-gray-400 hover:text-white transition-colors">Coverage</a>
            <a href="#mechanics" class="text-gray-400 hover:text-white transition-colors">Process</a>
            <a href="#apply" class="text-gray-400 hover:text-white transition-colors">Apply</a>
            <a href="#faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a>
            <a href="#terms" class="text-gray-400 hover:text-white transition-colors">Terms</a>
        </div>
    </div>
</div>

{{-- ========================================================
         COVERAGE
    ======================================================== --}}
<section id="coverage" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Support</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">What's Covered</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">The TGC100 Grant removes the financial barrier between a committed runner and the start line of The Great Cordillera 100.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['icon' => 'M3 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z|M13 5v2M13 11v2M13 17v2', 'title' => 'Registration', 'desc' => 'Full entry fee for TGC 100KM, covered.', 'amount' => '100%'],
                ['icon' => 'M2 20v-7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v7|M2 13V8a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v3|M2 20h20', 'title' => 'Accommodation', 'desc' => 'Lodging for race weekend, arranged by RiCON.', 'amount' => 'Covered'],
                ['icon' => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z|M14.5 9.5l-2 5-5 2 2-5 5-2Z', 'title' => 'Transpo Allowance', 'desc' => 'Toward travel to and from the race venue.', 'amount' => '₱1,500'],
                ['icon' => 'M6 8V6a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2|M4 8h16v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8Z|M4 13h16', 'title' => 'Misc. Allowance', 'desc' => 'Toward race-week and participation-related expenses.', 'amount' => '₱1,500'],
            ] as $card)
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6 hover:border-white/20 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-orange-500/10 text-orange-500 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        @foreach(explode('|', $card['icon']) as $path)
                        <path d="{{ $path }}"/>
                        @endforeach
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg mb-2">{{ $card['title'] }}</h3>
                <p class="text-gray-400 text-lg mb-5 min-h-[3rem]">{{ $card['desc'] }}</p>
                <p class="text-white font-black text-2xl">{{ $card['amount'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         MECHANICS
    ======================================================== --}}
<section id="mechanics" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Timeline</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Mechanics: How the Process Works</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">Three stages. Straightforward by design. We'd rather you spend the time training than chasing paperwork.</p>

        <div class="space-y-4">
            @foreach([
                ['num' => '01', 'meta' => 'Sep 7-13', 'title' => 'Application Window', 'text' => 'Submit the application form, including your questionnaire responses. Applications are reviewed on a rolling basis, so applying early gives your interview slot more room, but the window stays open through September 13, 11:59 PM PHT.'],
                ['num' => '02', 'meta' => '+2-3 Days', 'title' => 'Interview Invitation', 'text' => 'Shortlisted applicants receive an email invitation within 2-3 days of submitting their application. The interview is a short conversation, not a re-test. It\'s where we get to know the person behind the answers. Check your spam folder, emails come from RiCON\'s official address.'],
                ['num' => '03', 'meta' => 'Sep 18', 'title' => 'Grantee Announced', 'text' => 'The final grantee is announced September 18 via email and on RiCON\'s official channels. All applicants are notified of the outcome, whether selected or not.'],
            ] as $stage)
            <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl p-6 md:p-7">
                <div class="flex flex-col md:flex-row md:items-start gap-4 md:gap-8">
                    <div class="flex-none md:w-32">
                        <span class="block font-black text-3xl text-white mb-1">{{ $stage['num'] }}</span>
                        <span class="text-orange-500 text-xs font-bold uppercase tracking-wider">{{ $stage['meta'] }}</span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-1.5">{{ $stage['title'] }}</h3>
                        <p class="text-gray-400 text-lg leading-relaxed max-w-2xl">{{ $stage['text'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         APPLICATION FORM (static preview, not yet wired)
    ======================================================== --}}
<section id="apply" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Application</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">TGC100 Grant Application Form</h2>
        <p class="text-gray-400 mb-10 max-w-2xl leading-relaxed">All fields marked required (*) must be completed. Applications close September 13, 11:59 PM PHT.</p>

        <div class="bg-[#111111] border border-white/10 rounded-2xl overflow-hidden">

            <div id="tgcCheckpointBar" class="flex border-b border-white/10 overflow-x-auto">
                @foreach(['Personal', 'Background', 'About You', 'About Your Race', 'Contact & Consent'] as $i => $label)
                <div class="tgc-cp flex-1 min-w-[110px] px-4 py-4 border-r border-white/10 last:border-r-0 text-[11px] font-semibold uppercase tracking-wide {{ $i === 0 ? 'text-orange-500' : 'text-gray-500' }}" data-cp-index="{{ $i }}">
                    <span class="tgc-cp-num block font-black text-lg mb-1 {{ $i === 0 ? 'text-orange-500' : 'text-white' }}">{{ $i + 1 }}</span>{{ $label }}
                </div>
                @endforeach
            </div>

            <form id="tgcGrantForm" novalidate class="p-6 md:p-9">

                {{-- Step 0: Personal Information --}}
                <div class="tgc-step" data-index="0">
                    <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Personal Information</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Full Name <span class="text-orange-500">*</span></label>
                            <input type="text" name="full_name" required class="tgc-input">
                            <p class="tgc-error">Enter your full name.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Date of Birth <span class="text-orange-500">*</span></label>
                            <input type="date" name="date_of_birth" max="{{ now()->subYears(16)->toDateString() }}" required class="tgc-input">
                            <p class="tgc-error">Enter your date of birth.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Email Address <span class="text-orange-500">*</span></label>
                            <input type="email" name="email" required class="tgc-input">
                            <p class="tgc-error">Enter a valid email address.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Mobile Number <span class="text-orange-500">*</span></label>
                            <input type="tel" name="mobile_number" required class="tgc-input">
                            <p class="tgc-error">Enter your mobile number.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">City / Province of Residence <span class="text-orange-500">*</span></label>
                            <input type="text" name="city_province" required class="tgc-input">
                            <p class="tgc-error">Enter your city or province.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Occupation <span class="text-orange-500">*</span></label>
                            <input type="text" name="occupation" required class="tgc-input">
                            <p class="tgc-error">Enter your occupation.</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-white mb-1.5">Strava / Instagram / Facebook Profile (running-related) <span class="text-orange-500">*</span></label>
                        <input type="text" name="social_profile" required class="tgc-input">
                        <p class="tgc-error">Add at least one profile link.</p>
                    </div>
                </div>

                {{-- Step 1: Running Background --}}
                <div class="tgc-step hidden" data-index="1">
                    <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Running Background</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Years Running Trail / Ultra Distances <span class="text-orange-500">*</span></label>
                            <input type="text" name="years_running" required class="tgc-input">
                            <p class="tgc-error">This field is required.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Longest Distance Completed <span class="text-orange-500">*</span></label>
                            <select name="longest_distance" required class="tgc-input">
                                <option value="">Select one</option>
                                <option>Under 21K</option>
                                <option>21K</option>
                                <option>42K - Marathon</option>
                                <option>50K</option>
                                <option>60K</option>
                                <option>100K</option>
                                <option>100 Miles+</option>
                            </select>
                            <p class="tgc-error">Select your longest distance.</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-white mb-1.5">Notable Race History</label>
                        <textarea name="race_history" data-count="rh" class="tgc-input min-h-[100px]"></textarea>
                        <div class="flex justify-between gap-4 text-xs text-gray-500 mt-1.5">
                            <span>Races, distances, and years. Include DNFs, they're part of the record, not disqualifying.</span>
                            <span class="tgc-count flex-none" id="rh-count">0</span>
                        </div>
                    </div>
                </div>

                {{-- Step 2: About You --}}
                <div class="tgc-step hidden" data-index="2">
                    <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">About You</p>

                    <div class="border-l-2 border-orange-500 pl-4 mb-6">
                        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Q1</p>
                        <label class="block text-lg font-semibold text-white mb-1.5">Describe your current training approach and how you've stayed committed to the sport, including through setbacks. <span class="text-orange-500">*</span></label>
                        <textarea name="q1" required data-count="q1" class="tgc-input min-h-[100px]"></textarea>
                        <p class="tgc-error">This answer is required.</p>
                        <div class="flex justify-between gap-4 text-xs text-gray-500 mt-1.5">
                            <span>Read closely, specifics help.</span>
                            <span class="tgc-count flex-none" id="q1-count">0</span>
                        </div>
                    </div>
                    <div class="border-l-2 border-orange-500 pl-4 mb-6">
                        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Q2</p>
                        <label class="block text-lg font-semibold text-white mb-1.5">How have you contributed to your running or trail community, whether through organizing, mentoring, volunteering, advocacy, environmental stewardship, or simply supporting other runners? <span class="text-orange-500">*</span></label>
                        <textarea name="q2" required data-count="q2" class="tgc-input min-h-[100px]"></textarea>
                        <p class="tgc-error">This answer is required.</p>
                        <div class="flex justify-end text-xs text-gray-500 mt-1.5">
                            <span class="tgc-count flex-none" id="q2-count">0</span>
                        </div>
                    </div>
                    <div class="border-l-2 border-orange-500 pl-4">
                        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Q3</p>
                        <label class="block text-lg font-semibold text-white mb-1.5">Why does running matter to you? Be specific and honest. <span class="text-orange-500">*</span></label>
                        <textarea name="q3" required data-count="q3" class="tgc-input min-h-[100px]"></textarea>
                        <p class="tgc-error">This answer is required.</p>
                        <div class="flex justify-end text-xs text-gray-500 mt-1.5">
                            <span class="tgc-count flex-none" id="q3-count">0</span>
                        </div>
                    </div>
                </div>

                {{-- Step 3: About Your Race --}}
                <div class="tgc-step hidden" data-index="3">
                    <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">About Your Race</p>

                    <div class="border-l-2 border-orange-500 pl-4 mb-6">
                        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Q4</p>
                        <label class="block text-lg font-semibold text-white mb-1.5">Why The Great Cordillera 100, specifically, and why this year? <span class="text-orange-500">*</span></label>
                        <textarea name="q4" required data-count="q4" class="tgc-input min-h-[100px]"></textarea>
                        <p class="tgc-error">This answer is required.</p>
                        <div class="flex justify-end text-xs text-gray-500 mt-1.5">
                            <span class="tgc-count flex-none" id="q4-count">0</span>
                        </div>
                    </div>
                    <div class="border-l-2 border-orange-500 pl-4 mb-6">
                        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Q5</p>
                        <label class="block text-lg font-semibold text-white mb-1.5">Briefly describe how financial support would affect your ability to take part in The Great Cordillera 100.</label>
                        <textarea name="q5" data-count="q5" class="tgc-input min-h-[100px] mb-2"></textarea>
                        <div class="flex justify-between gap-4 text-xs text-gray-500 mb-4">
                            <span>Read only by the review panel.</span>
                            <span class="tgc-count flex-none" id="q5-count">0</span>
                        </div>
                        <label class="block text-lg font-semibold text-white mb-1.5">Employment / Income Status <span class="text-orange-500">*</span></label>
                        <select name="employment_status" required class="tgc-input">
                            <option value="">Select one</option>
                            <option>Employed Full-Time</option>
                            <option>Employed Part-Time / Freelance</option>
                            <option>Self-Employed</option>
                            <option>Student</option>
                            <option>Unemployed</option>
                            <option>Prefer to Explain Above</option>
                        </select>
                        <p class="tgc-error">Select your employment status.</p>
                    </div>
                    <div class="border-l-2 border-orange-500 pl-4">
                        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Q6</p>
                        <label class="block text-lg font-semibold text-white mb-1.5">What does taking on 100 kilometers mean to you at this point in your running journey, and what steps are you taking to prepare for it?</label>
                        <textarea name="q6" data-count="q6" class="tgc-input min-h-[100px]"></textarea>
                        <div class="flex justify-end text-xs text-gray-500 mt-1.5">
                            <span class="tgc-count flex-none" id="q6-count">0</span>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Emergency Contact & Consent --}}
                <div class="tgc-step hidden" data-index="4">
                    <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Emergency Contact</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Contact Name <span class="text-orange-500">*</span></label>
                            <input type="text" name="emergency_contact_name" required class="tgc-input">
                            <p class="tgc-error">This field is required.</p>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Contact Number <span class="text-orange-500">*</span></label>
                            <input type="tel" name="emergency_contact_number" required class="tgc-input">
                            <p class="tgc-error">This field is required.</p>
                        </div>
                    </div>

                    <p class="text-orange-500 text-xs font-bold uppercase tracking-wider border-b border-white/10 pb-2.5 mb-6">Media &amp; Documentation Consent</p>

                    <label class="flex items-start gap-3 text-lg text-gray-400 mb-4">
                        <input type="checkbox" name="consent_media" required class="mt-1 accent-orange-500 w-4 h-4 flex-none">
                        <span>I understand that, if selected, I agree to participate in reasonable interview, photography, filming, and documentation activities before, during, and/or after TGC100 for use in RiCON and The Great Cordillera 100 media and promotional content, as outlined in the Terms &amp; Conditions.</span>
                    </label>
                    <label class="flex items-start gap-3 text-lg text-gray-400 mb-4">
                        <input type="checkbox" name="consent_interview" required class="mt-1 accent-orange-500 w-4 h-4 flex-none">
                        <span>I understand that if shortlisted, I will be contacted for a short interview as part of the selection process.</span>
                    </label>
                    <label class="flex items-start gap-3 text-lg text-gray-400 mb-8">
                        <input type="checkbox" name="consent_terms" required class="mt-1 accent-orange-500 w-4 h-4 flex-none">
                        <span>I have read and agree to the TGC100 Grant Terms &amp; Conditions, and confirm that the information provided in this application is true and accurate to the best of my knowledge.</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Signature (type full name)</label>
                            <input type="text" name="signature" class="tgc-input">
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-white mb-1.5">Date</label>
                            <input type="date" name="signature_date" value="{{ now()->toDateString() }}" class="tgc-input">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
                    <button type="button" id="tgcBtnBack" disabled
                        class="py-3 px-6 text-lg font-bold rounded-lg border border-white/20 text-white hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent transition-colors">
                        Back
                    </button>
                    <span id="tgcStepStatus" class="hidden sm:inline text-[11px] font-semibold uppercase tracking-wide text-gray-500"></span>
                    <button type="button" id="tgcBtnNext"
                        class="py-3 px-6 text-lg font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                        Next
                    </button>
                </div>
            </form>

            <div id="tgcReviewPanel" class="hidden p-9 md:p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-orange-500/10 text-orange-500 flex items-center justify-center mx-auto mb-5 text-xl font-black">&check;</div>
                <h3 class="text-white font-bold text-xl mb-2">Application Received</h3>
                <p class="text-gray-400 max-w-md mx-auto mb-6 text-lg leading-relaxed">Thanks for applying for the TGC100 Grant. We've sent a confirmation to your email. We'll reach out if you're shortlisted for an interview, and all applicants are notified either way by September 18.</p>
                <dl id="tgcReviewSummary" class="text-left max-w-md mx-auto mb-6 rounded-lg border border-white/10 overflow-hidden divide-y divide-white/10"></dl>
                <p class="text-xs text-gray-500 border border-dashed border-white/20 rounded-lg px-4 py-3 max-w-md mx-auto text-left">Keep an eye on your inbox, including your spam folder, for updates from RiCON.</p>
            </div>
            <p id="tgcSubmitError" class="hidden px-6 md:px-9 pb-6 text-lg text-orange-500"></p>
        </div>
    </div>
</section>

{{-- ========================================================
         FAQ
    ======================================================== --}}
<section id="faq" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="text-center mb-12">
            <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-3">Questions</p>
            <h2 class="text-3xl md:text-4xl font-bold text-white">FAQ: Before You Apply</h2>
        </div>

        <div class="space-y-3" x-data="{ open: null }">
            @foreach([
                ['Who can apply?', 'Any runner intending to race The Great Cordillera 100KM who meets the race\'s own entry requirements. There is no elite-performance minimum. Grants are evaluated across all criteria, not on speed alone.'],
                ['What happens after I\'m selected?', 'The grantee will be onboarded with race logistics, media commitments, and disbursement details for the transportation and miscellaneous allowances. Full obligations are outlined in the Terms & Conditions.'],
                ['Do I need to already be registered for TGC100?', 'No. Registration is part of what the grant covers. Do not pay for your own registration in anticipation of the grant. RiCON will process it directly for the confirmed grantee.'],
            ] as $i => [$q, $a])
            <div class="bg-[#0d0d0d] rounded-xl overflow-hidden">
                <button type="button" @click="open = open === {{ $i }} ? null : {{ $i }}"
                    class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left">
                    <span class="text-white font-semibold text-lg md:text-xl">{{ $q }}</span>
                    <svg class="w-4 h-4 text-orange-500 flex-shrink-0 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
                    :class="open === {{ $i }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                    <div class="overflow-hidden">
                        <p class="text-gray-400 text-lg leading-relaxed px-6 pb-5">{{ $a }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         TERMS & CONDITIONS
    ======================================================== --}}
<section id="terms" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Effective for the 2026 Application Cycle</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">TGC100 Grant: Terms &amp; Conditions</h2>
        <p class="text-gray-400 mb-10 max-w-2xl leading-relaxed">By submitting an application for the TGC100 Grant, you agree to the terms below.</p>

        <div class="space-y-3" x-data="{ open: null }">
            @php
            $terms = [
                [
                    'title' => '1. Eligibility',
                    'text' => 'To apply for the TGC100 Grant, an applicant must be at least 18 years old at the time of application, or provide parental/guardian consent if between 16 and 17 years old; intend to compete in The Great Cordillera 100KM (November 13, 2026) and meet that race\'s own entry and qualification requirements; not have received a TGC100 Grant or equivalent full-coverage RiCON grant in the two most recent grant cycles; submit truthful, complete information, as RiCON reserves the right to verify any claim made in an application, including race history, community contributions, and financial disclosures; and not be a current employee, contractor, or immediate family member of RiCON staff directly involved in grant selection. Employees, contractors, and immediate family members of RiCON who are involved in the review or selection process are ineligible to apply in the cycle they participate in reviewing.',
                ],
                [
                    'title' => '2. Application & Selection Process',
                    'text' => 'The application window is September 7-13, 2026, closing 11:59 PM Philippine Time. Interview invitations are sent within approximately 2-3 business days of application submission via email, and RiCON is not responsible for invitations lost to spam filters, incorrect email addresses, or full inboxes. The grantee is announced September 18, 2026, when all applicants, selected and not selected, will be notified by email. These dates may shift due to circumstances outside RiCON\'s reasonable control, and any change will be posted on RiCON\'s official channels. Applications are evaluated by a review panel against five criteria: performance, dedication, and respect for the sport; impact to the community; personal development (why you run); relevance to TGC specifically; and financial need. No single criterion is weighted to guarantee selection on its own. Shortlisted applicants will be invited to a short interview (in person or video call) as part of final selection. Failure to respond to an interview invitation within the timeframe stated in the invitation email may result in disqualification from that cycle.',
                ],
                [
                    'title' => '3. Grant Coverage & Disbursement',
                    'text' => 'The TGC100 Grant covers race registration for TGC 100KM (arranged and paid directly by RiCON, the grantee must not pre-pay their own registration in anticipation of selection), accommodation for race weekend (arranged by RiCON), a transportation allowance of ₱1,500, and a miscellaneous allowance of ₱1,500, both disbursed ahead of race weekend. The grant does not cover gear, nutrition products, personal insurance, travel outside the immediate race weekend, or any costs beyond what is explicitly listed above. Allowances are disbursed once, are non-recurring, and are contingent on the grantee completing onboarding requirements set by RiCON.',
                ],
                [
                    'title' => '4. Grantee Obligations',
                    'text' => 'By accepting the TGC100 Grant, the grantee agrees to start and make a genuine, good-faith attempt to complete TGC 100KM (a DNF from injury, medical withdrawal, or legitimate race-day circumstance does not violate this obligation and is never treated as a failure of the grant); complete onboarding and respond to RiCON communications in a timely manner; participate in the media and documentation activities described in Section 5; represent the grant, RiCON, and the running community consistent with the Code of Conduct in Section 9; and not transfer, sell, or assign their grant status or associated benefits.',
                ],
                [
                    'title' => '5. Media, Documentation & RiCON Official Media Partners',
                    'text' => 'As a condition of accepting the TGC100 Grant, the grantee consents to be photographed, filmed, interviewed, and otherwise documented by RiCON before, during, and after TGC100 race weekend, for use across RiCON\'s owned channels and by RiCON\'s Official Media Partners. "RiCON Official Media Partners" means third-party media outlets, content creators, photographers, videographers, and publications formally accredited by RiCON to cover TGC100; the current list is available on request from partnership@ricon.ph and may change between cycles. Footage, photography, quotes, and story material gathered under this consent may be used in race recaps, brand and grant-program marketing, Official Media Partner publications and broadcasts, and archival or documentary content, without additional compensation to the grantee beyond the grant itself, unless a separate paid engagement is agreed in writing. RiCON commits to representing the grantee, including those who DNF, finish mid-pack, or finish at the back of the field, with the same respect and craft as elite finishers, and a DNF is never framed as a failure in RiCON or Official Media Partner content produced under this program. The grantee may request, in writing, that specific sensitive personal details be withheld from published content. RiCON will make reasonable efforts to honor such requests but cannot guarantee control over Official Media Partner editorial decisions once material has been shared under accreditation, and this media consent cannot be revoked retroactively for content already published.',
                ],
                [
                    'title' => '6. Withdrawal, Forfeiture & Replacement',
                    'text' => 'If the grantee withdraws before race weekend, they must notify RiCON in writing as soon as reasonably possible, and unused allowances must be returned if already disbursed and the grantee does not participate in any covered activity. RiCON reserves the right to revoke a grant, at its discretion, if the grantee provided materially false information, violates the Code of Conduct, or fails to complete onboarding without reasonable cause. RiCON may, at its discretion, offer a forfeited grant slot to a waitlisted applicant from the same cycle. If the grantee does not start TGC100 for a documented medical or emergency reason, they are not required to return disbursed allowances already spent on race-related preparation, at RiCON\'s discretion.',
                ],
                [
                    'title' => '7. Data Privacy',
                    'text' => 'Information submitted in the application, including financial disclosures, is used solely for grant evaluation, onboarding, and program administration, and is accessible only to the review panel and relevant RiCON staff. RiCON handles personal data in accordance with the Philippine Data Privacy Act of 2012 (RA 10173). Applicants may request access to, correction of, or deletion of their submitted data by writing to partnership@ricon.ph, subject to records RiCON is required to retain for program integrity. Financial-need responses are read only by the review panel and are not shared with Official Media Partners or published in any form.',
                ],
                [
                    'title' => '8. Liability & Assumption of Risk',
                    'text' => 'Ultra-distance trail running carries inherent physical risk, including but not limited to injury, altitude effects, exposure, and terrain hazards. The grantee acknowledges this risk independently of and in addition to any waiver required by the TGC100 race organizer, and agrees that RiCON\'s provision of a grant does not constitute a guarantee of safety, performance outcome, or fitness assessment. RiCON is not liable for injury, loss, or damages arising from a grantee\'s participation in TGC100 or related grant activities, except where caused by RiCON\'s own gross negligence or willful misconduct. The grantee is responsible for their own health clearance, travel insurance (if desired), and personal gear beyond what is explicitly covered under Section 3.',
                ],
                [
                    'title' => '9. Code of Conduct',
                    'text' => 'The grantee is expected to uphold the same standard RiCON holds itself to: respect for the mountains, the host communities, race officials, volunteers, and fellow runners at every level of the pack. Conduct that includes harassment, discrimination, dishonesty about race results, or disrespect toward host communities or event staff may result in immediate forfeiture of the grant and exclusion from future RiCON grant cycles.',
                ],
                [
                    'title' => '10. Discretion of RiCON',
                    'text' => 'All decisions relating to eligibility, selection, interview shortlisting, grant amounts, and forfeiture are made at RiCON\'s sole discretion and are final. RiCON is not obligated to disclose individual scoring, review notes, or reasons for non-selection, though general feedback may be offered where practical.',
                ],
                [
                    'title' => '11. General Provisions',
                    'text' => 'These Terms are governed by the laws of the Republic of the Philippines. RiCON may update these Terms between grant cycles, and the version in effect at the time of application governs that cycle. If any provision of these Terms is found unenforceable, the remaining provisions continue in full force. These Terms, together with the application form, constitute the entire agreement between RiCON and the applicant regarding the TGC100 Grant.',
                ],
                [
                    'title' => '12. Contact',
                    'text' => 'Questions about these Terms or the TGC100 Grant program can be directed to partnership@ricon.ph.',
                ],
            ];
            @endphp

            @foreach($terms as $index => $term)
            <div class="bg-[#111111] border border-white/10 rounded-2xl overflow-hidden">
                <button type="button" @click="open = open === {{ $index }} ? null : {{ $index }}"
                    class="w-full flex items-start gap-4 p-6 text-left">
                    <div class="w-7 h-7 rounded-full bg-orange-500/10 text-orange-500 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $index + 1 }}</div>
                    <h3 class="flex-1 text-white font-semibold text-lg">{{ $term['title'] }}</h3>
                    <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5 transition-transform" :class="open === {{ $index }} ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
                    :class="open === {{ $index }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                    <div class="overflow-hidden">
                        <p class="text-gray-400 text-lg leading-relaxed pl-11 pr-6 pb-6">{{ $term['text'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .tgc-input {
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
        border-radius: 0.5rem;
        border: 1px solid rgba(255,255,255,0.1);
        background: #1a1a1a;
        color: #fff;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
    .tgc-input:focus { outline: none; border-color: #f97316; }
    .tgc-input::placeholder { color: #6b7280; }
    input[type="date"].tgc-input {
        color-scheme: dark;
        -webkit-appearance: auto;
        appearance: auto;
        position: relative;
        min-width: 0;
        padding-left: 2.75rem;
    }
    input[type="date"].tgc-input::-webkit-calendar-picker-indicator {
        position: absolute;
        left: 0.75rem;
        cursor: pointer;
    }
    .tgc-error { display: none; color: #f97316; font-size: 0.75rem; margin-top: 0.375rem; }
    .tgc-field-invalid .tgc-input { border-color: #f97316; }
    .tgc-field-invalid .tgc-error { display: block; }
</style>

<script>
(function () {
    // ---- character counters ----
    document.querySelectorAll('#tgcGrantForm textarea[data-count]').forEach(function (ta) {
        var counter = document.getElementById(ta.dataset.count + '-count');
        function update() { counter.textContent = ta.value.length + ' chars'; }
        ta.addEventListener('input', update);
        update();
    });

    // ---- date inputs: open the native picker on click anywhere in the field ----
    document.querySelectorAll('#tgcGrantForm input[type="date"]').forEach(function (input) {
        input.addEventListener('click', function () {
            if (typeof input.showPicker === 'function') {
                try { input.showPicker(); } catch (e) {}
            }
        });
    });

    // ---- multi-step form ----
    var steps = Array.prototype.slice.call(document.querySelectorAll('#tgcGrantForm .tgc-step'));
    var checkpoints = Array.prototype.slice.call(document.querySelectorAll('#tgcCheckpointBar .tgc-cp'));
    var current = 0;
    var btnBack = document.getElementById('tgcBtnBack');
    var btnNext = document.getElementById('tgcBtnNext');
    var stepStatus = document.getElementById('tgcStepStatus');
    var form = document.getElementById('tgcGrantForm');
    var reviewPanel = document.getElementById('tgcReviewPanel');

    function renderStep() {
        steps.forEach(function (s, i) { s.classList.toggle('hidden', i !== current); });
        checkpoints.forEach(function (cp, i) {
            var isCurrent = i === current;
            var isDone = i < current;
            cp.classList.toggle('text-orange-500', isCurrent);
            cp.classList.toggle('text-gray-500', !isCurrent && !isDone);
            cp.classList.toggle('text-green-500', isDone);
            var num = cp.querySelector('.tgc-cp-num');
            num.classList.toggle('text-orange-500', isCurrent);
            num.classList.toggle('text-white', !isCurrent);
        });
        btnBack.disabled = current === 0;
        btnNext.textContent = current === steps.length - 1 ? 'Submit Application' : 'Next';
        stepStatus.textContent = 'Checkpoint ' + (current + 1) + ' of ' + steps.length;
    }

    function validateStep(index) {
        var fields = steps[index].querySelectorAll('[required]');
        var ok = true;
        var firstInvalid = null;
        fields.forEach(function (field) {
            var wrapper = field.closest('div') || field.parentElement;
            var isCheckbox = field.type === 'checkbox';
            var valid = isCheckbox ? field.checked : field.value.trim().length > 0;
            if (wrapper) wrapper.classList.toggle('tgc-field-invalid', !valid);
            if (!valid) { ok = false; if (!firstInvalid) firstInvalid = field; }
        });
        if (firstInvalid) firstInvalid.focus();
        return ok;
    }

    btnNext.addEventListener('click', function () {
        if (!validateStep(current)) return;
        if (current < steps.length - 1) { current++; renderStep(); }
        else { submitApplication(); }
    });
    btnBack.addEventListener('click', function () { if (current > 0) { current--; renderStep(); } });

    var submitError = document.getElementById('tgcSubmitError');

    function showSummary() {
        var summary = document.getElementById('tgcReviewSummary');
        summary.innerHTML = '';
        var rows = [
            ['Applicant', form.querySelector('[name="full_name"]').value],
            ['Email', form.querySelector('[name="email"]').value],
            ['Longest Distance', form.querySelector('[name="longest_distance"]').value],
            ['Employment Status', form.querySelector('[name="employment_status"]').value],
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
        return payload;
    }

    function submitApplication() {
        submitError.classList.add('hidden');
        submitError.textContent = '';
        btnNext.disabled = true;
        btnBack.disabled = true;
        var originalLabel = btnNext.textContent;
        btnNext.textContent = 'Submitting…';

        fetch("{{ route('programs.tgc100-grant.store') }}", {
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

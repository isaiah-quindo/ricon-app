@extends('layouts.public')
@section('title', 'Rules and Guidelines')

@section('content')
{{-- ======================================================== --}}
{{-- HERO --}}
{{-- ======================================================== --}}
<section class="relative min-h-[40vh] flex items-end overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gray-900"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-black/60 to-transparent"></div>
    <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
        <h1 class="text-5xl md:text-6xl font-black text-white leading-tight">
            Rules &amp; Guidelines
        </h1>
        <p class="text-gray-400 mt-3 text-lg">November 13, 2026 &mdash; Benguet, Cordillera, Philippines</p>
    </div>
</section>

{{-- ======================================================== --}}
{{-- QUICK NAV --}}
{{-- ======================================================== --}}
<div class="bg-[#111111] border-b border-white/10 sticky top-16 z-40">
    <div class="mx-auto px-8 overflow-x-auto" style="max-width:1280px;">
        <div class="flex items-center gap-6 h-12 text-sm whitespace-nowrap">
            <a href="#entry" class="text-gray-400 hover:text-white transition-colors">Entry Requirements</a>
            <a href="#general" class="text-gray-400 hover:text-white transition-colors">General Rules</a>
            <a href="#penalties" class="text-gray-400 hover:text-white transition-colors">Penalties</a>
            <a href="#dq" class="text-gray-400 hover:text-white transition-colors">Disqualifications</a>
            <a href="#crew" class="text-gray-400 hover:text-white transition-colors">Crew Rules</a>
            <a href="#cancellation" class="text-gray-400 hover:text-white transition-colors">Cancellation Policy</a>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- ENTRY REQUIREMENTS --}}
{{-- ======================================================== --}}
<section id="entry" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Section 1</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-10">Entry Requirements</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Age --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 font-bold text-sm flex items-center justify-center flex-shrink-0">1</div>
                    <h3 class="text-white font-semibold">Minimum Age Requirement</h3>
                </div>
                <p class="text-gray-400 text-sm mb-3">Participants must be at least:</p>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        20 years old for 100km
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        18 years old for 60km
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        16 years old for shorter distances (with parental consent)
                    </li>
                </ul>
            </div>

            {{-- Medical --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 font-bold text-sm flex items-center justify-center flex-shrink-0">2</div>
                    <h3 class="text-white font-semibold">Medical Fitness</h3>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">All participants must be in good physical condition and capable of completing the race distance. The organizer reserves the right to require a medical certificate for verification as required by the LGU.</p>
            </div>

            {{-- Trail Experience --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6 md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 font-bold text-sm flex items-center justify-center flex-shrink-0">3</div>
                    <h3 class="text-white font-semibold">Trail Experience & Preparation</h3>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">While no official qualifying races are mandatory, participants must possess adequate trail experience and maintain a high level of preparation.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-[#1a1a1a] rounded-xl p-4">
                        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">Self-Reliance</p>
                        <p class="text-gray-400 text-sm">Due to the remoteness of the trail, professional help may take longer than usual to reach you.</p>
                    </div>
                    <div class="bg-[#1a1a1a] rounded-xl p-4">
                        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">Personal Responsibility</p>
                        <p class="text-gray-400 text-sm">Your health and safety are your own priority. Do not hesitate to pull out if you cannot continue safely.</p>
                    </div>
                    <div class="bg-[#1a1a1a] rounded-xl p-4">
                        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">Course Hazards</p>
                        <p class="text-gray-400 text-sm">Expect steep climbs, rugged surfaces, and potential exposure to severe weather.</p>
                    </div>
                </div>
            </div>

            {{-- Mandatory Gear --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6 md:col-span-2">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 font-bold text-sm flex items-center justify-center flex-shrink-0">4</div>
                    <h3 class="text-white font-semibold">Mandatory Gear</h3>
                </div>
                <p class="text-gray-400 text-sm mb-6">All runners must carry the complete mandatory gear list at all times during the race. Random gear checks will be conducted.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- 100 KM --}}
                    <div class="bg-[#0d0d0d] rounded-xl p-4">
                        <span class="inline-block text-xs font-bold text-orange-500 bg-orange-500/10 px-2 py-0.5 rounded-full mb-3">100 KM</span>
                        <ul class="space-y-1.5">
                            @foreach(['Trail running shoes', 'Hydration pack (min. 1.5L)', 'Emergency space blanket', 'Headlamp + extra batteries', 'First aid kit', 'Whistle', 'Rain jacket', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="w-1 h-1 rounded-full bg-orange-500 flex-shrink-0"></span>{{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- 60 KM --}}
                    <div class="bg-[#0d0d0d] rounded-xl p-4">
                        <span class="inline-block text-xs font-bold text-red-400 bg-red-500/10 px-2 py-0.5 rounded-full mb-3">60 KM</span>
                        <ul class="space-y-1.5">
                            @foreach(['Trail running shoes', 'Hydration pack (min. 1.5L)', 'Emergency space blanket', 'Headlamp + extra batteries', 'First aid kit', 'Whistle', 'Rain jacket', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>{{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- 21 KM --}}
                    <div class="bg-[#0d0d0d] rounded-xl p-4">
                        <span class="inline-block text-xs font-bold text-green-400 bg-green-500/10 px-2 py-0.5 rounded-full mb-3">21 KM</span>
                        <ul class="space-y-1.5">
                            @foreach(['Trail running shoes', 'Hydration pack (min. 500ml)', 'Headlamp + extra batteries', 'First aid kit', 'Whistle', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="w-1 h-1 rounded-full bg-green-400 flex-shrink-0"></span>{{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- 15 KM --}}
                    <div class="bg-[#0d0d0d] rounded-xl p-4">
                        <span class="inline-block text-xs font-bold text-cyan-400 bg-cyan-500/10 px-2 py-0.5 rounded-full mb-3">15 KM</span>
                        <ul class="space-y-1.5">
                            @foreach(['Trail running shoes', 'Hydration (water bottle)', 'First aid kit', 'Whistle', 'Mobile phone (fully charged)', 'Race bib (provided)'] as $item)
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <span class="w-1 h-1 rounded-full bg-cyan-400 flex-shrink-0"></span>{{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

            {{-- Registration Completion --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 font-bold text-sm flex items-center justify-center flex-shrink-0">5</div>
                    <h3 class="text-white font-semibold">Registration Completion</h3>
                </div>
                <p class="text-gray-400 text-sm mb-3">Entry is only confirmed upon:</p>
                <ul class="space-y-2">
                    @foreach(['Full payment of registration fee', 'Submission of required documents', 'Acceptance of waiver and race rules'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Cut-off & Right to Decline --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 font-bold text-sm flex items-center justify-center flex-shrink-0">6</div>
                    <h3 class="text-white font-semibold">Cut-Off Awareness</h3>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">Participants must acknowledge and accept all cut-off times and race rules prior to joining. Intermediate cut-off times to be announced.</p>
            </div>

            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6 md:col-span-2">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 font-bold text-sm flex items-center justify-center flex-shrink-0">7</div>
                    <h3 class="text-white font-semibold">Right to Decline</h3>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">The Organizer reserves the right to decline any application from individuals who fail to meet established safety, health, or behavioral standards.</p>
            </div>

        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- GENERAL RULES --}}
{{-- ======================================================== --}}
<section id="general" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Section 2</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-10">General Rules &amp; Guidelines</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
            $rules = [
            ['num' => '1', 'title' => 'Follow Marked Course', 'text' => 'Runners must follow the official race route at all times. Shortcuts are strictly prohibited.'],
            ['num' => '2', 'title' => 'Bib Visibility', 'text' => 'Runners must wear their race bib visibly at the front at all times to allow checkpoint marshals to clearly identify and record their bib number upon passage.'],
            ['num' => '3', 'title' => 'Checkpoints', 'text' => 'All runners must check in and out of every checkpoint.'],
            ['num' => '4', 'title' => 'Environmental Responsibility', 'text' => 'Strictly no littering. Carry your own trash until the next aid station.'],
            ['num' => '5', 'title' => 'Respect Local Communities', 'text' => 'Participants must respect local residents, culture, and property along the course.'],
            ['num' => '6', 'title' => 'Support Crew & Pacers', 'text' => 'Crew support is allowed only at designated aid stations (to be announced). Pacers must be registered runners from start to finish.'],
            ['num' => '7', 'title' => 'Safety First', 'text' => 'Runners must follow instructions from race officials, marshals, and medical teams. Medical personnel have authority to remove a runner if continuing poses a safety risk.'],
            ['num' => '8', 'title' => 'Weather Conditions', 'text' => 'The race will proceed regardless of weather unless conditions are deemed unsafe by the race director and the LGU.'],
            ['num' => '9', 'title' => 'Withdrawal', 'text' => 'Participants who decide to withdraw must report to the nearest checkpoint immediately.'],
            ];
            @endphp
            @foreach($rules as $rule)
            <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-orange-500/10 text-orange-500 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $rule['num'] }}</div>
                    <div>
                        <h3 class="text-white font-semibold text-sm mb-1">{{ $rule['title'] }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $rule['text'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- PENALTIES --}}
{{-- ======================================================== --}}
<section id="penalties" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Section 3</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Penalties</h2>
        <p class="text-gray-400 mb-10">The following violations may result in time penalties at the discretion of race officials.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
            $penalties = [
            ['offense' => 'First offense littering', 'penalty' => '30 min'],
            ['offense' => 'Missing mandatory gear (minor)', 'penalty' => '15–30 min'],
            ['offense' => 'Unauthorized assistance', 'penalty' => '30 min'],
            ['offense' => 'Failure to follow checkpoint procedures', 'penalty' => '15 min'],
            ];
            @endphp
            @foreach($penalties as $p)
            <div class="bg-[#111111] border border-amber-500/20 rounded-2xl p-5 text-center">
                <p class="text-amber-400 font-black text-2xl mb-2">{{ $p['penalty'] }}</p>
                <p class="text-gray-400 text-sm">{{ $p['offense'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Race Director Rights --}}
        <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
            <h3 class="text-white font-semibold mb-4">Race Director's Authority</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach(['Modify the course, cut-off times, or rules for safety reasons', 'Cancel or suspend the race due to extreme conditions', 'Make final decisions on all disputes and rule interpretations'] as $right)
                <div class="flex items-start gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0 mt-2"></span>
                    <p class="text-gray-400 text-sm">{{ $right }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- DISQUALIFICATIONS --}}
{{-- ======================================================== --}}
<section id="dq" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Section 4</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Disqualifications</h2>
        <p class="text-gray-400 mb-10">The following actions will result in <span class="text-red-400 font-semibold">immediate disqualification</span>.</p>

        <div class="bg-[#0d0d0d] border border-red-500/20 rounded-2xl p-6 mb-6">
            <ul class="space-y-3">
                @php
                $dqs = [
                'Course cutting or deviation from the marked route. Failure to follow the prescribed course, including taking shortcuts or disregarding race signage.',
                'Bib swapping or running under another participant\'s name.',
                'Use of prohibited performance-enhancing substances.',
                'Severe misconduct towards race staff, volunteers, locals, other trail users, and/or volunteers.',
                'Repeated littering offenses.',
                'Failure to comply with instructions from race officials, marshals, emergency personnel, or volunteers.',
                'Refusal to undergo gear check at the starting line and failure to present mandatory gear at random points along the course.',
                'Continuing after cut-off times.',
                ];
                @endphp
                @foreach($dqs as $dq)
                <li class="flex items-start gap-3 text-sm text-gray-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0 mt-2"></span>
                    {{ $dq }}
                </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-red-500/10 border border-red-500/20 rounded-xl px-6 py-4">
            <p class="text-red-400 text-sm font-semibold">Disqualified runners will not be eligible for refunds, awards, or official results.</p>
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- CREW RULES --}}
{{-- ======================================================== --}}
<section id="crew" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Section 5</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Crew Rules</h2>
        <p class="text-gray-400 mb-10 max-w-2xl">All support crews must comply with the rules and directives of The Great Cordillera 100 as outlined in the Participant Guide, RiCON website, official Rules & Guidelines, and all race communications.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
            $crewRules = [
            'Crews must keep noise levels to a minimum, especially at aid stations in remote villages. Violations may result in disqualification of their runner.',
            'Crew access is allowed only at designated aid stations as specified in the TGC100 Participant Guide.',
            'Smoking is strictly prohibited at all aid stations and along the TGC100 course.',
            'Each runner is limited to one (1) crew vehicle at aid stations.',
            'Crews must drive safely and adhere to all speed limits. Many aid stations are accessed via narrow, winding, and hazardous roads.',
            'Bicycles & e-mountain bikes may be used to access crew-designated aid stations but are strictly prohibited for pacing or use on any part of the race course.',
            'Vehicles must not block traffic, trail access, aid stations, or other parked vehicles. Improperly parked vehicles may be towed at the owner\'s expense.',
            'Crews must follow all instructions from aid station personnel, including designated meeting areas and restricted zones.',
            'Crews must remain within a 50m radius of the aid station while supporting their runner, unless otherwise instructed.',
            'All support crews must register at the race village prior to deployment and secure official crew wristbands.',
            'Littering is strictly prohibited at aid stations, along the course, and at the finish line. All trash must be carried back to Baguio.',
            'Crews are encouraged to extend support and courtesy to runners without crews.',
            ];
            @endphp
            @foreach($crewRules as $rule)
            <div class="flex items-start gap-3 bg-[#111111] border border-white/10 rounded-xl p-4">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0 mt-2"></span>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $rule }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- CANCELLATION POLICY --}}
{{-- ======================================================== --}}
<section id="cancellation" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Section 6</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Event Cancellation Policy</h2>
        <p class="text-gray-400 mb-10 max-w-2xl">The Organizers reserve the right to shorten or cancel the event at any time if circumstances arise that threaten the safety of participants or volunteers.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-white font-semibold mb-4">Grounds for Cancellation</h3>
                <div class="space-y-3">
                    @php
                    $grounds = [
                    ['label' => 'Adverse Weather', 'desc' => 'Severe or dangerous meteorological conditions.'],
                    ['label' => 'Environmental Hazards', 'desc' => 'Landslides, rockfalls, or wildfires affecting the course.'],
                    ['label' => 'Access Issues', 'desc' => 'Protests, blockages, or trail/road closures.'],
                    ['label' => 'Force Majeure', 'desc' => 'Any incident beyond the control of the Organizers including acts of God, war, civil unrest, disease outbreaks, or threats of terrorism.'],
                    ];
                    @endphp
                    @foreach($grounds as $g)
                    <div class="flex items-start gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0 mt-2"></span>
                        <p class="text-sm text-gray-400"><span class="text-white font-medium">{{ $g['label'] }}:</span> {{ $g['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-[#0d0d0d] border border-amber-500/20 rounded-2xl p-6">
                <h3 class="text-white font-semibold mb-3">Policy on Fees</h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">In the event of a cancellation or shortening of the course, <span class="text-white font-semibold">no registration fees or charitable donations will be refunded, and no deferrals to future events will be offered.</span></p>
                <p class="text-amber-400 text-xs leading-relaxed">Note: By registering, you acknowledge and accept these terms regarding the potential cancellation of the event.</p>
            </div>
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- CTA --}}
{{-- ======================================================== --}}
<section class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8 text-center" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to take on the Cordillera?</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">You've read the rules. Now it's time to commit. Secure your slot before they run out.</p>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
            Register Now
        </a>
    </div>
</section>

{{-- ======================================================== --}}
{{-- FOOTER --}}
{{-- ======================================================== --}}

@endsection
@extends('layouts.public')
@section('title', 'FAQs')
@section('og_title', 'FAQs — The Great Cordillera 100 Ultra Trail')
@section('og_description', 'Everything you need before race week at The Great Cordillera 100. Getting to Baguio, where to stay, kit claiming, bib policy, safety, and support crew.')

@section('content')
@php
$link = 'text-orange-500 underline decoration-orange-500/30 underline-offset-2 hover:decoration-orange-500';
$btn = 'inline-flex items-center gap-x-2 py-3 px-4 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors no-underline';

$sections = [
    [
        'id' => 'getting-here',
        'nav' => 'Getting Here',
        'eyebrow' => 'Getting Here',
        'title' => 'Getting to Baguio',
        'items' => [
            [
                'q' => "I'm not from Baguio. What's the realistic way to get there?",
                'a' => "<p>Land travel from Metro Manila is the default and what most runners use, whether you've flown into NAIA or you're already local. There's no realistic way to reach Baguio without a bus or car leg at some point, since the city's own airport doesn't run regular commercial flights.</p>",
            ],
            [
                'q' => "Taking the bus? Here's the step-by-step commute.",
                'steps' => [
                    ['From NAIA', 'Book a ride with the Grab app to a Pasay or PITX bus terminal.'],
                    ['From Clark International Airport', 'Book a ride to the Dau Bus Terminal in Clark, then catch a bus bound for Baguio City. It\'s a shorter ride than starting from Manila.'],
                    ['From Manila', 'Book a bus from PITX, Pasay, or Cubao going to Baguio City. <span class="text-white font-semibold">About 5&ndash;6 hours.</span>'
                        . '<br>&middot; Victory Liner: <a href="https://www.victoryliner.com" target="_blank" rel="noopener" class="' . $link . '">victoryliner.com</a>'
                        . '<br>&middot; Genesis / JoyBus: <a href="https://iwantseats.com" target="_blank" rel="noopener" class="' . $link . '">iwantseats.com</a>'
                        . '<br>&middot; Solid North: <a href="https://bookna.com/solidnorthinc/" target="_blank" rel="noopener" class="' . $link . '">bookna.com/solidnorthinc</a>'],
                    ['In Baguio', 'From the Baguio bus terminal, hail a cab to <span class="text-white font-semibold">Scout Hill, Camp John Hay</span>, the race venue.'
                        . '<br><a href="https://maps.app.goo.gl/Ht1bawxE2ndVLZgZ9" target="_blank" rel="noopener" class="' . $link . '">Open in Google Maps</a>'],
                ],
            ],
            [
                'q' => 'Driving instead? Here are the routes.',
                'steps' => [
                    ['From Manila', 'NLEX &rarr; SCTEX &rarr; TPLEX &rarr; Rosario exit &rarr; Marcos Highway (or Kennon Road when it\'s open). <span class="text-white font-semibold">About 5&ndash;6 hours.</span>'],
                    ['From Clark International Airport', 'SCTEX &rarr; TPLEX &rarr; Rosario exit &rarr; Marcos Highway (or Kennon Road when it\'s open). <span class="text-white font-semibold">Roughly half the drive time from Manila</span> since you\'re already north of NLEX.'],
                ],
                'tip' => ['Kennon Road note', 'Kennon Road closes for repairs and landslide clearing without much warning. Check its status the week of your trip and have Marcos Highway as your default.'],
            ],
            [
                'q' => 'Prefer to skip the buses and driving? RiCON is happy to offer a shuttle.',
                'a' => '<p>RiCON runs a group shuttle van from Manila straight to Camp John Hay, so you can skip booking your own bus or driving up. Pick your terminal, we handle the rest.</p>'
                    . '<p><a href="' . route('shuttle') . '" class="' . $btn . '">Reserve a shuttle seat &rarr;</a></p>'
                    . '<p>Each shuttle date runs once a minimum number of seats is booked. If a date doesn\'t fill up, we\'ll cancel that run and email everyone booked in time to sort out another way up.</p>',
            ],
        ],
    ],
    [
        'id' => 'accommodation',
        'nav' => 'Stay',
        'eyebrow' => 'Accommodation',
        'title' => 'Where to stay',
        'items' => [
            [
                'q' => 'Where should I book?',
                'a' => '<p>The race venue sits within Camp John Hay, home to John Hay Hotels, RiCON\'s Official Hotel Partner for The Great Cordillera. A John Hay Hotels stay puts you within a <span class="text-white font-semibold">5&ndash;10 minute walk</span> from the starting line, not a commute.</p>'
                    . '<div class="flex items-center gap-4 bg-[#111111] border border-white/10 hover:border-orange-500/50 rounded-xl p-4 transition-colors">'
                    . '<div class="w-11 h-11 rounded-lg bg-orange-500/10 text-orange-500 font-extrabold flex items-center justify-center flex-shrink-0">JH</div>'
                    . '<div><p class="text-white font-semibold">John Hay Hotels</p><p class="text-gray-500 text-sm">Official Hotel Partner &middot; Camp John Hay, Baguio City</p></div>'
                    . '</div>'
                    . '<p><a href="https://johnhayhotels.com" target="_blank" rel="noopener" class="' . $link . '">johnhayhotels.com</a></p>',
            ],
            [
                'q' => 'Is there a special rate for TGC100 runners?',
                'a' => '<p>Yes. John Hay Hotels will be offering special accommodation rates for TGC100 runners and their support crews.</p>'
                    . '<p>Interested in staying at John Hay Hotels for race weekend? Send us an email and we\'ll help you get the details.</p>'
                    . '<p><a href="mailto:info@ricon.ph" class="' . $btn . '">Email info@ricon.ph &rarr;</a></p>',
            ],
            [
                'q' => 'Do I need to book early?',
                'a' => '<p>Race weekend lands in Baguio\'s cool-season peak, when the city fills up on its own. Book as soon as your training block is confirmed, not the week of.</p>',
            ],
        ],
    ],
    [
        'id' => 'good-to-know',
        'nav' => 'Good to Know',
        'eyebrow' => 'First-Timers',
        'title' => 'Good to know before you land',
        'items' => [
            [
                'q' => 'Do I need to speak Filipino to get around?',
                'a' => '<p>No. English is widely spoken throughout Baguio, especially anywhere you\'ll actually be as a visiting runner: hotels, terminals, and the race venue itself.</p>',
            ],
            [
                'q' => 'What currency do I need, and where do I get cash?',
                'a' => '<p>Philippine Peso (PHP). ATMs and money changers are common in Baguio City center, less so once you\'re at Camp John Hay itself. Withdraw what you need before heading up to the venue.</p>',
            ],
            [
                'q' => 'What time zone is Baguio in?',
                'a' => '<p>Philippine Standard Time, UTC+8, year-round. The country doesn\'t observe daylight saving, so the offset from your home time zone won\'t shift between when you book and when you race.</p>',
            ],
            [
                'q' => 'Will my phone have signal on course, so people can track me?',
                'a' => '<p>Intermittent cellular signal throughout the course. Signal in the race village is reliable. If you\'re picking a SIM or roaming plan for the trip, <span class="text-white font-semibold">Smart</span> carries the best coverage along the route.</p>',
            ],
            [
                'q' => 'Is there a training program I can follow before race day?',
                'a' => '<p>Yes. RiCON runs an official training program built around Cordillera-specific terrain and elevation, not a generic road-race plan. <a href="' . route('training.landing') . '" class="' . $link . '">See the training program</a> for enrollment details.</p>',
            ],
        ],
    ],
    [
        'id' => 'prep',
        'nav' => 'Weather & Altitude',
        'eyebrow' => 'Prep',
        'title' => 'Weather and altitude',
        'items' => [
            [
                'q' => 'How cold does it actually get?',
                'a' => '<p>Camp John Hay sits around 1,500M above sea level. November mornings can drop into the low teens (Celsius) before the sun\'s up, then run warm at midday on exposed ridgeline. Pack layers you can shed mid-race, not just cold-weather gear you wear at the start line and regret by kilometer 20.</p>',
            ],
            [
                'q' => 'Will the altitude affect my race?',
                'a' => '<p>The 100K course alone climbs to 6,124M of elevation gain. That\'s enough to notice if you\'re arriving straight from sea level, especially on the early ascents. If this is your first time racing at elevation, give yourself a day or two in Baguio before the gun goes off rather than flying in the night before.</p>',
            ],
        ],
    ],
    [
        'id' => 'kit-bib',
        'nav' => 'Kit & Bib Policy',
        'eyebrow' => 'Race Weekend',
        'title' => 'Race kit &amp; bib policy',
        'items' => [
            [
                'q' => 'When can I claim my race kit?',
                'a' => '<p>Kit claiming runs the day before each distance\'s gun start, split by race day:</p>',
                'steps' => [
                    ['100KM &amp; 60KM', 'Friday, November 13 &middot; <span class="text-white font-semibold">8AM&ndash;5PM</span>'],
                    ['21KM &amp; 10KM', 'Saturday, November 14 &middot; <span class="text-white font-semibold">8AM&ndash;5PM</span>'],
                ],
                'after' => '<p>If you\'re new to the course and traveling in from outside Baguio, this is also your buffer day. Don\'t plan to arrive with just enough time to grab your bib and sleep. Failure to claim your kit means you can\'t participate.</p>',
            ],
            [
                'q' => 'What actually happens when I show up to claim my kit?',
                'steps' => [
                    ['Gear Check', 'Mandatory gear inspection happens first. Bring your complete mandatory gear list for verification before your kit is released.'],
                    ['Kit Claiming', 'Present a valid ID at the claiming table. A photo (mugshot-style) is taken on-site as part of identity verification before your race kit is handed over.'],
                    ['Race Day', 'Final check-in at the start line includes another mandatory gear check before you\'re cleared to start.'],
                ],
            ],
            [
                'q' => 'What do I need to bring for kit claiming?',
                'a' => '<p>Your signed waiver, a valid ID, and your mandatory gear list for inspection. Missing any one of the three will hold up your claim.</p>',
            ],
            [
                'q' => "Can I claim my bib on November 13 if I'm running the 10K or 21K?",
                'a' => '<p>Yes. Kit claiming on November 13 is open to all distances. Priority, however, goes to 100K and 60K runners ahead of their earlier start times.</p>',
            ],
            [
                'q' => 'Am I allowed to race if I arrive late, post-gun start?',
                'a' => '<p>Yes, you\'re welcome to continue with race participation as long as you\'re within the <span class="text-white font-semibold">grace period of 10 minutes</span>. Take note of the cut-off times along the course, though. A late start doesn\'t move them.</p>',
            ],
            [
                'q' => 'Can I switch distance/category on race day, or transfer my bib?',
                'a' => '<p>No. Race bibs are non-transferable and your registered distance is final. Anyone caught racing under another name will be disqualified and banned from future RiCON events.</p>',
            ],
            [
                'q' => 'Can someone else claim my race kit for me?',
                'a' => '<p>Yes. Your representative needs a signed Authorization Letter stating the reason for the authorization, plus a valid ID from both you and the person claiming on your behalf.</p>',
            ],
            [
                'q' => 'Are there gear checks throughout the event?',
                'a' => '<p>Yes, during kit claiming and again at random along the course at aid stations. Make sure you\'re fully equipped with the required gear well before race day rather than gambling on not getting checked.</p>',
            ],
        ],
    ],
    [
        'id' => 'safety',
        'nav' => 'Safety & DNF',
        'eyebrow' => 'Runner Safety',
        'title' => 'Safety &amp; DNF',
        'items' => [
            [
                'q' => 'What are my options if I need to DNF?',
                'a' => '<p>If you decide to stop, or race staff advise you to, surrender your bib at the nearest aid station. Aid station personnel will do their best to coordinate a motorcycle or service vehicle for extraction, <span class="text-white font-semibold">at the runner\'s expense</span>. Extraction availability depends heavily on how remote that aid station is. Not every aid station on course can guarantee a way out.</p>'
                    . '<p>In life-threatening circumstances, race organizers coordinate emergency response directly, regardless of the aid station\'s position on the extraction list. Outside of that, we cannot guarantee route extraction from aid stations not on the official DNF list. Runners are ultimately responsible for their own safety on course. See the official Race Manual for the full list of drop-off points.</p>',
            ],
            [
                'q' => 'Is there medical support on the course?',
                'a' => '<p>Yes. We ensure adequate medical support at each aid station along every distance.</p>',
            ],
        ],
    ],
    [
        'id' => 'support',
        'nav' => 'Support & Crew',
        'eyebrow' => 'Spectators &amp; Crew',
        'title' => 'Support &amp; pacers',
        'items' => [
            [
                'q' => 'Can I accompany my runner on course?',
                'a' => '<p>For the majority of the course, unfortunately no. Pacers aren\'t allowed along the route, for the safety of everyone involved. The exception is the <span class="text-white font-semibold">final 3km of the 100K</span> (Happy Hollow), where pacers, support crew, and friends and family can join their runner in for a proper celebration at the finish. <span class="text-white font-semibold">Maximum of 2 pacers per runner.</span></p>',
            ],
            [
                'q' => 'Do you allow support crews at aid stations?',
                'a' => '<p>Yes, strictly for the 100K and 60K categories, and only at specific, designated aid stations. Refer to the official Race Manual\'s Support Crew section for exactly which ones.</p>',
            ],
            [
                'q' => 'Is there parking at the race venue? What about at crewable aid stations?',
                'a' => '<p>Yes. There\'s ample parking throughout the Camp John Hay property (keep your wristband and ID on you at all times). Crewable aid stations have designated parking too, with marshals and parking personnel on hand. Follow all traffic laws and the directions in the Race Manual and from marshals on the ground.</p>',
            ],
        ],
    ],
    [
        'id' => 'course-data',
        'nav' => 'Course Data',
        'eyebrow' => 'Course Data',
        'title' => 'Distance &amp; elevation',
        'items' => [
            [
                'q' => 'Why is the elevation gain different on my watch or app?',
                'a' => '<p>Every GPS watch and platform (Strava, Garmin, COROS, Suunto) applies its own smoothing to raw GPS and barometric data, so the same course can post a different D+ number depending on what recorded it and what processed it afterward. Treat RiCON\'s official race-day figures as the authoritative ones for cut-offs and course stats, not whatever your personal device says at the finish line.</p>',
            ],
        ],
    ],
];

$facts = [
    ['Race Weekend', 'Nov 13&ndash;15, 2026'],
    ['Venue', 'Camp John Hay'],
    ['Distances', '100 &middot; 60 &middot; 21 &middot; 10K'],
    ['Top Elevation Gain', '6,124M D+'],
];
@endphp

{{-- ======================================================== --}}
{{-- HERO --}}
{{-- ======================================================== --}}
<section class="relative min-h-[40vh] flex items-end overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gray-900"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-black/60 to-transparent"></div>
    <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100 &middot; FAQs</p>
        <h1 class="text-5xl md:text-6xl font-black text-white leading-tight">
            Getting to the start line
        </h1>
        <p class="text-gray-400 mt-3 text-lg max-w-3xl">Everything you need before race week, with extra attention for runners coming in from outside Baguio: <span class="text-white font-semibold">how to get here, where to sleep, and the policies that matter once you're on course.</span></p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-white/10 border border-white/10 rounded-2xl overflow-hidden mt-10 max-w-4xl">
            @foreach($facts as [$k, $v])
            <div class="bg-[#111111] px-5 py-4">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">{{ $k }}</p>
                <p class="text-white font-extrabold">{!! $v !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- QUICK NAV --}}
{{-- ======================================================== --}}
<div class="bg-[#111111] border-b border-white/10 sticky top-16 z-40">
    <div class="mx-auto px-8 overflow-x-auto" style="max-width:1280px;">
        <div class="flex items-center gap-6 h-12 text-sm whitespace-nowrap">
            @foreach($sections as $section)
            <a href="#{{ $section['id'] }}" class="text-gray-400 hover:text-white transition-colors">{{ $section['nav'] }}</a>
            @endforeach
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- FAQ SECTIONS --}}
{{-- ======================================================== --}}
@foreach($sections as $section)
<section id="{{ $section['id'] }}" class="{{ $loop->odd ? 'bg-[#0d0d0d]' : 'bg-[#111111]' }} py-20 scroll-mt-28">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">{!! $section['eyebrow'] !!}</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-10">{!! $section['title'] !!}</h2>

        <div class="max-w-4xl space-y-3">
            @foreach($section['items'] as $item)
            <div x-data="{ open: false }"
                :class="open ? 'border-orange-500/40' : 'border-white/10'"
                class="{{ $loop->parent->odd ? 'bg-[#111111]' : 'bg-[#0d0d0d]' }} border rounded-2xl transition-colors">
                <button type="button" @click="open = !open" :aria-expanded="open"
                    class="group w-full flex items-center justify-between gap-4 text-left px-6 py-5">
                    <span class="text-white font-semibold group-hover:text-orange-500 transition-colors">{{ $item['q'] }}</span>
                    <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300"
                        :class="open ? 'rotate-180 text-orange-500' : 'text-gray-500'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <div class="px-6 pb-6 text-gray-400 text-sm leading-relaxed space-y-4">
                        @isset($item['a'])
                        {!! $item['a'] !!}
                        @endisset

                        @isset($item['steps'])
                        <div class="space-y-2">
                            @foreach($item['steps'] as [$label, $detail])
                            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-1 sm:gap-4 {{ $loop->parent->parent->odd ? 'bg-[#0d0d0d]' : 'bg-[#111111]' }} rounded-xl px-4 py-3">
                                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider pt-0.5">{!! $label !!}</p>
                                <p class="text-gray-400 text-sm leading-relaxed">{!! $detail !!}</p>
                            </div>
                            @endforeach
                        </div>
                        @endisset

                        @isset($item['tip'])
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl px-5 py-4">
                            <p class="text-amber-400 text-xs font-semibold uppercase tracking-wider mb-1">{{ $item['tip'][0] }}</p>
                            <p class="text-gray-300 text-sm">{{ $item['tip'][1] }}</p>
                        </div>
                        @endisset

                        @isset($item['after'])
                        {!! $item['after'] !!}
                        @endisset
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach

{{-- ======================================================== --}}
{{-- CTA --}}
{{-- ======================================================== --}}
<section class="bg-[#0d0d0d] py-20 border-t border-white/10">
    <div class="mx-auto px-8 text-center" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Still have a question?</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">Reach RiCON directly. We'd rather answer it now than have you find out on race day.</p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="mailto:info@ricon.ph" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                Email info@ricon.ph
            </a>
            <a href="https://www.facebook.com/profile.php?id=61585439769463" target="_blank" rel="noopener noreferrer" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg border border-white/20 text-white hover:border-white/50 transition-colors">
                Message us on Facebook
            </a>
        </div>
    </div>
</section>

@endsection

@extends('layouts.public')
@section('title', 'TGC 21 KM')
@section('og_title', 'TGC 21 KM — The Great Cordillera Trail Run')
@section('og_description', 'Race 21 kilometers through the breathtaking Cordillera mountains at RICON. A challenging course for seasoned trail runners. Register and take on the adventure.')

@section('content')
{{-- ========================================================
         HERO
    ======================================================== --}}
<section class="relative min-h-[58vh] flex items-end overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-gray-600 text-sm select-none">
        <img src="/images/21km-hero.png" alt="TGC21KM" class="w-full h-full object-cover" />
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
    <div class="relative grid grid-cols-1 z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
        <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-3">
            TGC <span class="text-green-400">21 KM</span>
        </h1>
        <span class="block p-1 my-2 justify-self-start bg-white/50">
            <a href="https://utmb.world/utmb-index" target="_blank">
                <img src="/images/index-20K.png" class="w-[150px]" alt="UTMB Index 20K"/>
            </a>
        </span>
        <p class="text-gray-300 text-lg">Province of Benguet</p>
    </div>
</section>


{{-- ========================================================
         STATS BAR
    ======================================================== --}}
<div class="bg-green-600 border-b border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-2 md:grid-cols-6 md:divide-x divide-white/20">
            <div class="py-8 px-6 first:pl-0">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Distance</p>
                <p class="text-white font-black text-2xl">21 KM</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-0">Est. Elevation Gain</p>
                <p class="text-white font-black text-2xl">1300M D+</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                <p class="text-white font-black text-2xl">8 hrs</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Race Date</p>
                <p class="text-white font-black text-2xl">Nov 15, 2026</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Gunstart</p>
                <p class="text-white font-black text-2xl">5 AM</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Start & Finish</p>
                <p class="text-white font-black text-2xl">Baguio City</p>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================
         ABOUT THE RACE
    ======================================================== --}}
<section class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-3">About the Race</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">The TGC 21 KM</h2>
                <p class="text-gray-400 leading-relaxed mb-4">A half-marathon distance that refuses to behave like one. Two significant climbs, one river ford, and a final descent that will ask everything of your quads. Finish this and you will know whether 50 is in your future.
                </p>
                <!-- <ul class="mt-4 space-y-2">
                    @foreach(['1,300 M+ elevation gain', 'Pine forests and scenic ridgelines', 'Aid stations along the route', 'Open to 18 and above'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul> -->
            </div>
            <div class="bg-gray-700 rounded-2xl h-80 flex items-center justify-center text-gray-500 text-sm select-none overflow-hidden">
                <img src="/images/race-photo.png" alt="Race Photo" class="w-full h-full object-cover" />
            </div>
        </div>
    </div>
</section>


{{-- ========================================================
         Inclusions
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 items-center">
            <div class="col-span-1 md:col-span-2">
                <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-3">Inclusions</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">What's included in your registration</h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    From the moment you register to the moment you finish, we've got you covered; gear, timing, fuel, and a medal to prove you earned it.
                </p>
                <ul class="grid grid-cols-2 gap-x-6 gap-y-2 mb-6">
                    @foreach(['Race bib', 'Timing Chip', 'Finisher medal', 'Event shirt', 'Event tote bag', 'Post-race meal', 'Race day insurance', 'Event stickers', 'Municipality Fees', 'Environmental Fees', 'Barangay Fees'] as $item)
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-700 rounded-2xl h-80 flex items-center justify-center text-gray-500 text-sm select-none overflow-hidden">
                <img src="/images/inclusions/21-1.png" alt="Inclusion 1" class="w-full h-full object-cover" />
            </div>
        </div>
    </div>
</section>

<section class="min-h-[95px] bg-[#1C3D20]" style="background-image: url('/images/pattern-1.svg'); background-repeat: repeat-x; background-size: auto 100%; background-position: center center;">
</section>


{{-- ========================================================
         Registration Fee
    ======================================================== --}}
<section class="bg-[#1C3D20] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-2">Registration Fee</p>
        <h2 class="text-3xl font-bold text-white mb-6">Secure your slot</h2>
        <p class="text-white leading-relaxed mb-6">
            Lock in your place on the start line before it sells out.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="relative bg-[#1a1a1a] bg-opacity-30 rounded-xl p-5 border border-white/5 text-center opacity-60">
                <p class="absolute top-3 right-3 text-gray-400 text-xs bg-gray-700/60 px-2 py-0.5 rounded-full">Ended</p>
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Super Early Bird</p>
                <p class="text-gray-400 font-bold text-2xl mb-1 line-through">₱1,990</p>
                <p class="text-gray-500 text-sm">April 15 - May 15</p>
            </div>
            <div class="relative bg-[#1a1a1a] bg-opacity-30 rounded-xl p-5 border border-white/5 text-center opacity-60">
                <p class="absolute top-3 right-3 text-gray-400 text-xs bg-gray-700/60 px-2 py-0.5 rounded-full">Ended</p>
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Early Bird</p>
                <p class="text-gray-400 font-bold text-2xl mb-1 line-through">₱2,490</p>
                <p class="text-gray-500 text-sm">May 16 - Jun 30</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="relative bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border-green-500 border text-center">
                <p class="absolute top-3 right-3 text-green-400 text-xs">⚠ Limited slots</p>
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Regular</p>
                <p class="text-white font-bold text-2xl mb-1">₱3,000</p>
                <p class="text-gray-500 text-sm">Jul 1 - Aug 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱3,500</p>
                <p class="text-gray-500 text-sm">Aug 16 - Sep 15</p>
            </div>
            <div class="bg-[#1a1a1a] bg-opacity-50 rounded-xl p-5 border border-white/5 text-center">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-1">Super Late</p>
                <p class="text-white font-bold text-2xl mb-1">₱4,000</p>
                <p class="text-gray-500 text-sm">Sep 16 - Sep 30</p>
            </div>
        </div>
        <p class="text-gray-300 text-xs leading-relaxed mb-6">
            Registration fees are subject to change without notice.
        </p>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            Register Now
        </a>
    </div>
</section>

<section class="min-h-[95px] bg-[#102A14]" style="background-image: url('/images/pattern-2.svg'); background-repeat: repeat-x; background-position: center center;">
</section>


{{-- ========================================================
         READINESS QUIZ
    ======================================================== --}}
<section id="readiness-quiz" class="bg-[#111111] py-24" x-data="tgc21kQuiz()">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="max-w-2xl mx-auto">
            <p class="text-green-400 text-sm font-semibold uppercase tracking-wider mb-2 text-center">Readiness Check</p>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-3 text-center">Are you ready for TGC 21K?</h2>
            <p class="text-gray-400 mb-10 text-center">2 minutes. 5 questions. An honest answer.</p>

            <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl overflow-hidden">
                {{-- Progress --}}
                <div class="h-1 bg-white/5">
                    <div class="h-full bg-green-500 transition-all duration-500" :style="`width:${progress}%`"></div>
                </div>

                <div class="p-6 md:p-10">
                    {{-- Welcome --}}
                    <template x-if="screen === 'welcome'">
                        <div>
                            <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-4">The Great Cordillera · Nov 15, 2026</p>
                            <p class="text-gray-300 leading-relaxed mb-8 max-w-md">Five quick questions about where your running is today. At the end, you get an honest read: go for the 21K, build up to it, or start with the 10K.</p>
                            <button type="button" @click="start"
                                class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-hidden">
                                Find out
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    {{-- Questions --}}
                    <template x-if="screen === 'question'">
                        <div>
                            <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-3" x-text="`Question ${qIndex + 1} of ${questions.length}`"></p>
                            <h3 class="text-xl md:text-2xl font-bold text-white mb-6" x-text="questions[qIndex].text"></h3>
                            <div class="space-y-2.5">
                                <template x-for="(option, i) in questions[qIndex].options" :key="`${qIndex}-${i}`">
                                    <button type="button" @click="pick(i, option.pts)"
                                        class="w-full flex items-center gap-4 px-5 py-4 rounded-xl border text-left transition-colors"
                                        :class="selected === i ? 'border-green-500 bg-green-500/10' : 'border-white/10 hover:border-green-500/50 hover:bg-green-500/5'">
                                        <span class="w-8 h-8 flex-shrink-0 rounded-lg border flex items-center justify-center text-xs font-bold transition-colors"
                                            :class="selected === i ? 'bg-green-600 border-green-600 text-white' : 'border-white/20 text-gray-400'"
                                            x-text="['A','B','C'][i]"></span>
                                        <span class="text-sm font-medium transition-colors" :class="selected === i ? 'text-white' : 'text-gray-300'" x-text="option.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Collect --}}
                    <template x-if="screen === 'collect'">
                        <div>
                            <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-3">Almost there</p>
                            <h3 class="text-xl md:text-2xl font-bold text-white mb-2">Where do we send your result?</h3>
                            <p class="text-gray-400 text-sm mb-6 max-w-sm">Enter your name and email. We'll show you your result and keep you posted on TGC 2026.</p>
                            <form @submit.prevent="submit" class="space-y-4 max-w-sm">
                                <div>
                                    <label for="quizName" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">First name</label>
                                    <input id="quizName" type="text" x-model="name" autocomplete="given-name" placeholder="Your first name"
                                        class="w-full rounded-lg border border-white/10 bg-white/5 text-white text-sm px-3.5 py-2.5 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" />
                                </div>
                                <div>
                                    <label for="quizEmail" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Email address</label>
                                    <input id="quizEmail" type="email" x-model="email" autocomplete="email" placeholder="you@example.com"
                                        class="w-full rounded-lg border border-white/10 bg-white/5 text-white text-sm px-3.5 py-2.5 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" />
                                </div>
                                <p x-show="error" x-cloak class="text-red-400 text-xs" x-text="error"></p>
                                <button type="submit" :disabled="submitting"
                                    class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                                    <span x-text="submitting ? 'One moment…' : 'See my result'"></span>
                                </button>
                            </form>
                        </div>
                    </template>

                    {{-- Result --}}
                    <template x-if="screen === 'result'">
                        <div>
                            <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-3">Your Result</p>
                            <h3 class="text-2xl md:text-3xl font-black text-white mb-4" x-text="results[result].title"></h3>
                            <div class="w-9 h-0.5 bg-green-500 mb-5"></div>
                            <p class="text-gray-400 leading-relaxed mb-8 max-w-lg whitespace-pre-line" x-text="results[result].body"></p>
                            <div class="flex flex-wrap items-center gap-5">
                                <a href="{{ route('registration.create') }}"
                                    class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-hidden">
                                    <span x-text="results[result].cta"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                                <a x-show="result === 'c'" x-cloak href="{{ route('race-category.10km') }}"
                                    class="text-sm text-green-400 hover:text-green-300 transition-colors font-medium">
                                    View the TGC 10K course
                                </a>
                            </div>
                            <button type="button" @click="retake"
                                class="mt-6 block text-xs text-gray-500 hover:text-gray-300 transition-colors">
                                Retake the quiz
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ========================================================
         REQUIREMENTS
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-3">Requirements</p>
                <h2 class="text-xl font-bold text-white mb-5">Mandatory Gear</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Trail running shoes', 'Hydration pack (500ml)', 'Emergency blanket', 'Headlamp + extra batteries (fully charged)', 'First aid kit', 'Whistle', 'Windbreaker', 'Mobile phone (fully charged)', 'Race bib (provided)', 'Extra cash', 'Ziploc bag for your trash'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-green-400 hover:text-green-300 transition-colors font-medium">
                    View full gear & entry rules
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Recommended Gear --}}
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-3">Recommended</p>
                <h2 class="text-xl font-bold text-white mb-5">Recommended Gear</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['*Utensils (cups, bowls and sporks)', 'Anti-chafing cream (vaseline, petroleum jelly)', 'Trekking poles', 'Ice banda', 'Sunscreen', 'Sun glasses', 'Insect repellent lotion', 'Cap or sun hat', 'Spare socks', 'Spare top in case of dropout', 'Headlamp + extra batteries', 'Spare batteries', 'Power bank'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <p class="text-gray-400 text-xs pb-4">* No disposable utensils will be provided at the aid station. Kindly make sure you have your own utensils to enjoy the different beverage and food items at the aid station.</p>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-green-400 hover:text-green-300 transition-colors font-medium">
                    View full gear & entry rules
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider mb-3">Qualifications</p>
                <h2 class="text-xl font-bold text-white mb-5">Who Can Join</h2>
                <ul class="space-y-2 mb-6">
                    @foreach (['Minimum age: 18 years old on race day', 'Valid government-issued ID', 'Medical clearance may be required', 'Liability waiver must be signed', 'Full payment of registration fee'] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('rules') }}#entry" class="inline-flex items-center gap-1.5 text-sm text-green-400 hover:text-green-300 transition-colors font-medium">
                    View full entry requirements
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>


{{-- ========================================================
         CTA
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8 text-center" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to take on TGC 21?</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">
            Secure your slot now. Registration slots are limited.
        </p>
        <a href="{{ route('registration.create') }}" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
            Register Now
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function tgc21kQuiz() {
        return {
            screen: 'welcome', // 'welcome' | 'question' | 'collect' | 'result'
            qIndex: 0,
            score: 0,
            selected: null,
            locked: false,
            name: '',
            email: '',
            submitting: false,
            error: '',
            result: 'c',
            questions: [
                { text: 'How far can you run today without stopping?', options: [
                    { label: '10km or more', pts: 3 },
                    { label: '5 to 9km', pts: 2 },
                    { label: 'Less than 5km', pts: 1 },
                ]},
                { text: 'How many hours a week can you commit to training?', options: [
                    { label: '5 hours or more', pts: 3 },
                    { label: '3 to 4 hours', pts: 2 },
                    { label: '1 to 2 hours', pts: 1 },
                ]},
                { text: 'Have you ever run or hiked a significant uphill for 30 minutes or more?', options: [
                    { label: 'Yes, regularly', pts: 3 },
                    { label: 'A few times', pts: 2 },
                    { label: 'Not really', pts: 1 },
                ]},
                { text: 'Where do you do most of your running?', options: [
                    { label: 'Trails: uneven ground, roots, mud', pts: 3 },
                    { label: 'Mix of road and light trail', pts: 2 },
                    { label: 'Road or treadmill', pts: 1 },
                ]},
                { text: 'Four months of consistent training. Honest answer: can you commit?', options: [
                    { label: "Yes. I've done it before.", pts: 3 },
                    { label: 'I think so. Never really tested it.', pts: 2 },
                    { label: "That's a stretch for me right now.", pts: 1 },
                ]},
            ],
            results: {
                a: {
                    title: '21K is yours. Register.',
                    body: "Your scores say you're already in a good position. Four months gives you time to train smart, not from scratch.\n\nThe Cordillera will ask something real of you in November. Based on where you're starting, you can answer it.\n\nSlots are limited. Register before the price goes up.",
                    cta: 'Register Now',
                },
                b: {
                    title: "21K is achievable. Here's the plan.",
                    body: "You're not quite there yet, but four months is a long time if you use it well.\n\nMost runners who finish TGC 21K didn't start at peak fitness. They started early and stayed consistent.\n\nRegister now and give yourself the runway to get ready.",
                    cta: 'Register Now',
                },
                c: {
                    title: 'TGC 10K is the right call.',
                    body: "Based on where you are right now, the 10K puts you on the right mountain without setting you up to struggle.\n\nAnd it gets you to the start line with something to improve next year.\n\nRegister for the 10K. Come back for the 21K when the time is right.",
                    cta: 'Register for TGC 10K',
                },
            },

            get progress() {
                if (this.screen === 'welcome') return 0;
                if (this.screen === 'question') return ((this.qIndex + 1) / 7) * 100;
                if (this.screen === 'collect') return (6 / 7) * 100;
                return 100;
            },

            start() {
                this.score = 0;
                this.qIndex = 0;
                this.selected = null;
                this.error = '';
                this.screen = 'question';
            },

            pick(i, pts) {
                if (this.locked) return;
                this.locked = true;
                this.selected = i;
                this.score += pts;

                setTimeout(() => {
                    this.selected = null;
                    this.locked = false;
                    if (this.qIndex < this.questions.length - 1) {
                        this.qIndex++;
                    } else {
                        this.screen = 'collect';
                    }
                }, 400);
            },

            async submit() {
                this.error = '';
                if (!this.name.trim()) { this.error = 'Please enter your first name.'; return; }
                if (!this.email.trim() || !this.email.includes('@')) { this.error = 'Please enter a valid email address.'; return; }

                this.submitting = true;
                let serverResult = null;
                try {
                    const res = await fetch("{{ route('race-category.21km.quiz') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                ?? "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            first_name: this.name.trim(),
                            email: this.email.trim(),
                            score: this.score,
                        }),
                    });

                    if (res.status === 429) {
                        this.error = 'Too many attempts. Please wait a minute and try again.';
                        return;
                    }

                    if (res.status === 422) {
                        const json = await res.json();
                        this.error = json.errors ? Object.values(json.errors)[0][0] : 'Please check your details and try again.';
                        return;
                    }

                    if (res.ok) {
                        serverResult = (await res.json()).result ?? null;
                        if (typeof fbq === 'function') fbq('track', 'Lead');
                    }
                } catch (e) {
                    // A network hiccup shouldn't hold the result hostage
                } finally {
                    this.submitting = false;
                }

                this.showResult(serverResult);
            },

            showResult(r) {
                this.result = r ?? (this.score >= 13 ? 'a' : this.score >= 8 ? 'b' : 'c');
                this.screen = 'result';
            },

            retake() {
                this.error = '';
                this.screen = 'welcome';
            },
        };
    }
</script>
@endpush
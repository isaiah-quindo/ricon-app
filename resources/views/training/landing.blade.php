@extends('layouts.public')
@section('title', 'Free 24-Week Training Program')
@section('og_title', 'Train for The Great Cordillera: Free 24-Week Program')
@section('og_description', 'A free week-by-week ultra trail training program by Edify Endurance. 100K and 60K plans. One shared 24-week calendar. Jump in at the current week and follow along. No login needed.')

@section('content')

{{-- Flag for the expired-link notice on the signup card --}}
<script>
    window.__tgcExpired = new URLSearchParams(location.search).has('expired');
</script>

{{-- ========================================================
         HERO
    ======================================================== --}}
<section class="relative overflow-hidden pt-16">
    {{-- Homepage-style dark base with warm orange glows --}}
    <div class="absolute inset-0 select-none" style="background-color:#0d0d0d; background-image:
        radial-gradient(ellipse 75% 110% at 0% 55%, rgba(234,88,12,0.35), transparent 62%),
        radial-gradient(ellipse 55% 65% at 100% 12%, rgba(249,115,22,0.25), transparent 58%),
        radial-gradient(ellipse 65% 80% at 98% 88%, rgba(194,65,12,0.25), transparent 60%);"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

    <div class="relative z-10 w-full mx-auto px-8 py-16 lg:py-24" style="max-width:1280px;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
                <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">Free Training Program · by Edify Endurance</p>
                <h1 class="text-5xl md:text-6xl xl:text-7xl font-black text-white leading-tight mb-4">
                    24 weeks.<br>One <span class="text-orange-500">mountain.</span>
                </h1>
                <p class="text-gray-300 text-lg max-w-xl mb-6">
                    The structured week-by-week plan that takes you from flat-city runs to 7,000m of Cordillera vert. The program is live and on <strong class="text-white">Week {{ $currentWeek }} of 24</strong> right now. Jump in and follow along.
                </p>
                <ul class="space-y-2 mb-8">
                    @foreach([
                        '100% free, no catch, no card',
                        'No account or password, ever',
                        'Racing TGC not required, everyone\'s welcome',
                    ] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="#how-it-works" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg border border-white/20 text-white hover:bg-white/10 transition-colors">
                    How it works
                </a>
            </div>

            {{-- Opt-in form --}}
            @include('training._signup_card')
        </div>
    </div>
</section>

{{-- ========================================================
         STATS BAR
    ======================================================== --}}
<div class="bg-[#1a1a1a] border-y border-white/10">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-2 md:grid-cols-4 md:divide-x divide-white/10">
            <div class="py-8 px-6 first:pl-0">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Now On</p>
                <p class="text-white font-black text-2xl">Week {{ $currentWeek }} of 24</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Plans</p>
                <p class="text-white font-black text-2xl">100K &amp; 60K</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Weekly Volume</p>
                <p class="text-white font-black text-2xl">5–16 hrs</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Cost</p>
                <p class="text-white font-black text-2xl">Free</p>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================
         WHAT YOU GET
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">What You Get</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Every week planned. Every day laid out.</h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    This isn't a generic running plan. It's a mountain-specific block built by Edify Endurance for The Great Cordillera: long climbs, loaded hiking, downhill control, and the strength work to hold it all together. Suitable even if your city is completely flat.
                </p>
                <ul class="space-y-2 mb-8">
                    @foreach([
                        'A full 7-day schedule for all 24 weeks',
                        'Key sessions, RPE targets, and vert goals for every run',
                        'Periodized blocks: Base → Build → Peak → Taper, with deloads built in',
                        'Weekly coach briefs that tell you what matters and why',
                        'Works around stairs, treadmills, and urban vert, no mountains required',
                    ] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="#signup" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden">
                    Get the Program
                </a>
            </div>

            {{-- Sample week preview --}}
            <div class="bg-[#1a1a1a] rounded-xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Sample: Week 1, Base 1</p>
                        <p class="text-white font-bold">Your first week looks like this</p>
                    </div>
                    <span class="text-xs font-bold text-orange-500 border border-orange-500/30 rounded-md px-2 py-1">7 hours</span>
                </div>
                <ul class="space-y-2">
                    @foreach([
                        ['MON', 'Rest', '', false],
                        ['TUE', 'Easy', '1h · RPE 2–3', false],
                        ['WED', 'Easy + Strides', '1h', false],
                        ['THU', 'Easy', '1h · RPE 2', false],
                        ['FRI', 'Rest', '', false],
                        ['SAT', 'Hilly Run', '2h · 300m vert', true],
                        ['SUN', 'Incline Treadmill / Stairs', '1h', false],
                    ] as [$day, $title, $meta, $key])
                    <li class="flex items-center gap-4 rounded-lg border px-4 py-3 {{ $key ? 'border-orange-500 bg-orange-500/10' : ($title === 'Rest' ? 'border-white/5 opacity-40' : 'border-white/10') }}">
                        <span class="w-10 text-[10px] font-bold tracking-wider {{ $key ? 'text-orange-500' : 'text-gray-500' }}">{{ $day }}</span>
                        <span class="flex-1 text-sm {{ $key ? 'text-white font-semibold' : 'text-gray-300' }}">{{ $title }}</span>
                        <span class="text-xs text-gray-500">{{ $meta }}</span>
                        @if($key)
                        <span class="text-[9px] font-bold uppercase tracking-widest text-orange-500">Key</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
         HOW IT WORKS
    ======================================================== --}}
<section id="how-it-works" class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="text-center mb-14">
            <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">How It Works</p>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">No app. No login. No excuses.</h2>
            <p class="text-gray-400 max-w-xl mx-auto">One shared calendar for the whole community, whether you're racing TGC or just want to get mountain-strong.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach([
                ['1', 'Sign up free', 'First name, email, and your distance: 100K or 60K. That\'s it.'],
                ['2', 'Get your personal link', 'We email you a private link to your program. Bookmark it. It works on any device, no password ever.'],
                ['3', 'Join the current week', "The whole program runs on one calendar and it's on Week {$currentWeek} right now. You train alongside everyone else."],
                ['4', 'Follow along weekly', 'All 24 weeks are open, past weeks included. Check the current week, do the work, come back next Monday.'],
            ] as [$num, $title, $text])
            <div class="bg-[#1a1a1a] rounded-xl p-6">
                <div class="w-10 h-10 rounded-lg bg-orange-500/10 text-orange-500 font-black text-lg flex items-center justify-center mb-4">{{ $num }}</div>
                <h3 class="text-white font-bold mb-2">{{ $title }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $text }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         PROGRAM STRUCTURE
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">Program Structure</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Built in blocks, like the pros train</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">
            Every 4-week block ends with a deload week. The work sticks when you recover. Volume climbs from easy aerobic base to full-pack peak weeks, then tapers so you arrive sharp.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            @foreach([
                ['Base', 'Weeks 1–9', 'Build the aerobic floor. Easy volume, light hills, time on feet.', 'bg-green-600'],
                ['Build', 'Weeks 11–14', 'Sharpen the climb and descent. Stairway loops, urban vert, downhill control.', 'bg-amber-600'],
                ['Peak', 'Weeks 16–19', 'The hardest weeks. Long days on tired legs, full pack, race-specific vert.', 'bg-red-600'],
                ['Taper', 'Weeks 21–23', 'Volume drops, the edge stays. Your fitness is in the bank.', 'bg-purple-600'],
                ['Goal Week', 'Week 24', 'You\'re here. The 24 weeks are done. Time to climb.', 'bg-yellow-600'],
            ] as [$phase, $range, $text, $color])
            <div class="bg-[#1a1a1a] rounded-xl p-6">
                <div class="h-1.5 w-12 rounded-full {{ $color }} mb-4"></div>
                <h3 class="text-white font-bold">{{ $phase }}</h3>
                <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-3">{{ $range }}</p>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $text }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         PREMIUM UPSELL (late joiners)
    ======================================================== --}}
@if($currentWeek > 1)
<section class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="rounded-xl border border-orange-500/30">
            <div class="rounded-xl bg-[#1a1a1a] px-8 py-12 md:px-14 md:py-14">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-center">
                    <div class="md:col-span-2">
                        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">Missed the early weeks?</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Late to the party? There's a plan for that.</h2>
                        <p class="text-gray-400 leading-relaxed mb-4">
                            The free program is already {{ $currentWeek }} weeks in, and those early base weeks do a lot of quiet work. You can absolutely still jump in, but if you want a plan built around where your fitness actually is right now, the coaches behind this program can help.
                        </p>
                        <p class="text-gray-400 leading-relaxed">
                            Edify Endurance offers premium, personalized training plans tailored to your current fitness, your schedule, and your goal race. Reach out for rates.
                        </p>
                    </div>
                    <div class="flex flex-col items-start md:items-center gap-3">
                        <a href="mailto:edifyendurance@gmail.com?subject=Personalized%20Training%20Plan%20Inquiry"
                            class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden">
                            Email Edify Endurance
                        </a>
                        <p class="text-gray-500 text-xs">edifyendurance@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ========================================================
         FAQ
    ======================================================== --}}
<section class="bg-[#0d0d0d] py-24">
    <div class="mx-auto px-8" style="max-width:768px;">
        <div class="text-center mb-12">
            <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-3">FAQ</p>
            <h2 class="text-3xl md:text-4xl font-bold text-white">Quick answers</h2>
        </div>

        <div class="space-y-3" x-data="{ open: null }">
            @foreach([
                ['Is it really free?', 'Yes. The full 24-week program is free: every week, every session. No card, no trial, no upsell.'],
                ['Do I have to be registered for The Great Cordillera?', 'No. The program is open to everyone. It\'s built for TGC\'s terrain, but it works for any mountain goal, or for just getting seriously fit.'],
                ['What if I lose my link?', 'Use "Re-send my link" on this page and we\'ll email it again. Your progress is tied to your start date, so nothing is lost.'],
                ['I\'m joining late. Did I miss too much?', 'You can still jump in: the plan always shows the current week, and every past week stays open to review. If you want the early base phases rebuilt around your timeline, Edify Endurance offers personalized premium plans. Email edifyendurance@gmail.com for rates.'],
                ['I live somewhere flat. Can I still follow it?', 'Yes. The plan is written for flat-city runners. Hill sessions map to stairways, incline treadmills, parking ramps, and whatever vert you can find.'],
            ] as $i => [$q, $a])
            <div class="bg-[#1a1a1a] rounded-xl overflow-hidden">
                <button type="button" @click="open = open === {{ $i }} ? null : {{ $i }}"
                    class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left">
                    <span class="text-white font-semibold text-sm md:text-base">{{ $q }}</span>
                    <svg class="w-4 h-4 text-orange-500 flex-shrink-0 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === {{ $i }}" x-transition.opacity x-cloak class="px-6 pb-5">
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $a }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         FINAL CTA
    ======================================================== --}}
<section class="bg-[#111111] py-24">
    <div class="mx-auto px-8 text-center" style="max-width:1280px;">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">The mountain doesn't care when you start.<br>But your legs will.</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">
            Every week you wait is a week of training you don't get back. The current week starts now.
        </p>
        <a href="#signup" class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 focus:outline-hidden">
            Start Training Free
        </a>
    </div>
</section>

<script>
    function trainingSignup() {
        return {
            name: '',
            email: '',
            registered: false,
            plan: 'tgc100k',
            submitting: false,
            redirecting: false,
            done: null,
            expired: !!window.__tgcExpired,
            resendOpen: false,
            resendEmail: '',
            toast: { visible: false, type: 'success', message: '' },
            toastTimer: null,

            showToast(message, type = 'success') {
                clearTimeout(this.toastTimer);
                this.toast = { visible: true, type, message };
                this.toastTimer = setTimeout(() => { this.toast.visible = false; }, 4500);
            },

            async submit() {
                if (!this.name.trim()) { this.showToast('Please enter your first name.', 'error'); return; }
                if (!this.email.trim() || !this.email.includes('@')) { this.showToast('Please enter a valid email address.', 'error'); return; }

                this.submitting = true;
                try {
                    const res = await fetch("{{ route('training.signup') }}", {
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
                            plan: this.plan,
                            registered_tgc: this.registered,
                        }),
                    });

                    if (res.status === 429) {
                        this.showToast('Too many attempts. Please wait a minute and try again.', 'error');
                        return;
                    }

                    const json = await res.json();

                    if (res.status === 422) {
                        this.showToast(json.errors ? Object.values(json.errors)[0][0] : 'Please check your details and try again.', 'error');
                        return;
                    }

                    if (json.status === 'created' && json.url) {
                        if (typeof fbq === 'function') fbq('track', 'Lead');
                        this.redirecting = true;
                        this.showToast("You're in, " + this.name.trim() + "! We also emailed your personal link to " + this.email.trim() + ". Taking you to your program now.");
                        setTimeout(() => location.assign(json.url), 2200);
                        return;
                    }

                    if (json.status === 'existing') {
                        this.done = 'existing';
                        this.showToast('We re-sent your personal link to ' + this.email.trim() + '. Check your inbox.');
                        return;
                    }

                    this.showToast('Something went wrong. Please try again.', 'error');
                } catch (e) {
                    this.showToast('Something went wrong. Please try again.', 'error');
                } finally {
                    if (!this.redirecting) this.submitting = false;
                }
            },

            async resend() {
                if (!this.resendEmail.trim() || !this.resendEmail.includes('@')) {
                    this.showToast('Please enter a valid email address.', 'error');
                    return;
                }
                try {
                    await fetch("{{ route('training.resend') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                ?? "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({ email: this.resendEmail.trim() }),
                    });
                } catch (e) {}
                this.showToast('If that email has a program, the link is on its way.');
            },
        };
    }
</script>

@endsection

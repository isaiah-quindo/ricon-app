@extends('layouts.public')
@section('title', 'Shuttle RSVP')
@section('og_title', 'Shuttle RSVP — The Great Cordillera 100 Ultra Trail')
@section('og_description', 'Reserve a seat on RiCON\'s group shuttle van from Manila straight to Camp John Hay for The Great Cordillera 100.')

@use('App\Models\ShuttleRsvp')

@section('content')
@php
$input = 'w-full bg-[#0a0a0a] border border-white/10 rounded-lg text-white text-sm px-4 py-3 placeholder-gray-600 focus:border-orange-500 focus:ring-0 focus:outline-none transition-colors';
$label = 'block text-sm font-medium text-gray-300 mb-1.5';

$facts = [
    ['Drop-off', 'Camp John Hay'],
    ['Runs', 'Kit-claiming day'],
    ['Pickup Points', count(ShuttleRsvp::PICKUP_POINTS) . ' options'],
];
@endphp

{{-- ======================================================== --}}
{{-- HERO --}}
{{-- ======================================================== --}}
<section class="relative min-h-[40vh] flex items-end overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gray-900"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-black/60 to-transparent"></div>
    <div class="relative z-10 w-full mx-auto px-8 pb-16" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100 &middot; Shuttle RSVP</p>
        <h1 class="text-5xl md:text-6xl font-black text-white leading-tight">
            Manila &rarr; Baguio shuttle
        </h1>
        <p class="text-gray-400 mt-3 text-lg max-w-3xl">For runners coming in from outside Baguio: reserve a seat on RiCON's group shuttle van for a convenient ride straight to Camp John Hay. <span class="text-white font-semibold">Tell us your preferred pickup point and we'll confirm details by email.</span></p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-px bg-white/10 border border-white/10 rounded-2xl overflow-hidden mt-10 max-w-3xl">
            @foreach($facts as [$k, $v])
            <div class="bg-[#111111] px-5 py-4">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">{{ $k }}</p>
                <p class="text-white font-extrabold">{{ $v }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- RSVP FORM --}}
{{-- ======================================================== --}}
<section class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="max-w-2xl mx-auto bg-[#111111] border border-white/10 rounded-2xl p-6 md:p-8"
            x-data="shuttleRsvp()">

            <form x-show="!submitted" @submit.prevent="submit()" novalidate class="space-y-10">

                {{-- Your details --}}
                <fieldset class="space-y-4">
                    <legend class="text-white font-bold text-lg mb-4">Your details</legend>
                    <div>
                        <label for="full_name" class="{{ $label }}">Full name <span class="text-orange-500">*</span></label>
                        <input type="text" id="full_name" x-model="form.full_name" autocomplete="name" class="{{ $input }}" :class="errors.full_name && '!border-red-500'">
                        <p x-show="errors.full_name" class="text-red-400 text-xs mt-1.5">Please enter your name.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="{{ $label }}">Email <span class="text-orange-500">*</span></label>
                            <input type="email" id="email" x-model="form.email" autocomplete="email" class="{{ $input }}" :class="errors.email && '!border-red-500'">
                            <p x-show="errors.email" class="text-red-400 text-xs mt-1.5">Please enter a valid email.</p>
                        </div>
                        <div>
                            <label for="mobile_number" class="{{ $label }}">Mobile number <span class="text-orange-500">*</span></label>
                            <input type="tel" id="mobile_number" x-model="form.mobile_number" autocomplete="tel" placeholder="09XX XXX XXXX" class="{{ $input }}" :class="errors.mobile_number && '!border-red-500'">
                            <p x-show="errors.mobile_number" class="text-red-400 text-xs mt-1.5">Please enter a mobile number.</p>
                        </div>
                    </div>
                </fieldset>

                {{-- Race details --}}
                <fieldset class="space-y-4">
                    <legend class="text-white font-bold text-lg mb-4">Race details</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="distance" class="{{ $label }}">Distance <span class="text-orange-500">*</span></label>
                            <select id="distance" x-model="form.distance" class="{{ $input }}" :class="errors.distance && '!border-red-500'">
                                <option value="" disabled>Select one</option>
                                @foreach(ShuttleRsvp::DISTANCES as $distance)
                                <option value="{{ $distance }}">{{ $distance }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.distance" class="text-red-400 text-xs mt-1.5">Please select your distance.</p>
                        </div>
                        <div>
                            <label for="seats" class="{{ $label }}">Seats needed <span class="text-orange-500">*</span></label>
                            <input type="number" id="seats" x-model="form.seats" min="1" max="{{ ShuttleRsvp::MAX_SEATS }}" class="{{ $input }}" :class="errors.seats && '!border-red-500'">
                            <p x-show="errors.seats" class="text-red-400 text-xs mt-1.5">Enter 1 to {{ ShuttleRsvp::MAX_SEATS }} seats.</p>
                        </div>
                    </div>
                    <div>
                        <label for="shuttle_date" class="{{ $label }}">Preferred shuttle date <span class="text-orange-500">*</span></label>
                        <select id="shuttle_date" x-model="form.shuttle_date" class="{{ $input }}" :class="errors.shuttle_date && '!border-red-500'">
                            <option value="" disabled>Select one</option>
                            @foreach(ShuttleRsvp::SHUTTLE_DATES as $value => $dateLabel)
                            <option value="{{ $value }}">{{ $dateLabel }}</option>
                            @endforeach
                        </select>
                        <p x-show="errors.shuttle_date" class="text-red-400 text-xs mt-1.5">Please select a shuttle date.</p>
                    </div>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" x-model="form.return_trip" class="mt-0.5 rounded border-white/20 bg-[#0a0a0a] text-orange-600 focus:ring-orange-500 focus:ring-offset-0">
                        <span class="text-sm text-gray-300">Also reserve a return seat, Baguio &rarr; Manila, after the race</span>
                    </label>
                </fieldset>

                {{-- Pickup point --}}
                <fieldset>
                    <legend class="text-white font-bold text-lg mb-4">Preferred pickup point <span class="text-orange-500">*</span></legend>
                    <div class="space-y-2" role="radiogroup">
                        @foreach(ShuttleRsvp::PICKUP_POINTS as $value => $sub)
                        <label class="flex items-start gap-3 bg-[#0a0a0a] border rounded-xl px-4 py-3 cursor-pointer transition-colors hover:border-orange-500/50"
                            :class="form.pickup_point === '{{ $value }}' ? 'border-orange-500' : 'border-white/10'">
                            <input type="radio" name="pickup_point" value="{{ $value }}" x-model="form.pickup_point" class="mt-1 border-white/20 bg-[#0a0a0a] text-orange-600 focus:ring-orange-500 focus:ring-offset-0">
                            <span>
                                <span class="block text-white font-semibold text-sm">{{ $value }}</span>
                                <span class="block text-gray-500 text-xs mt-0.5">{{ $sub }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <p x-show="errors.pickup_point" class="text-red-400 text-xs mt-1.5">Please choose a pickup point.</p>
                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl px-5 py-4 mt-4">
                        <p class="text-gray-300 text-sm leading-relaxed">Pickup points run based on demand. <span class="text-white font-semibold">A location with very few riders may get consolidated with the nearest option, or dropped if there isn't enough interest.</span> We'll confirm your final pickup point by email before race week.</p>
                    </div>
                </fieldset>

                {{-- Notes --}}
                <fieldset>
                    <legend class="text-white font-bold text-lg mb-4">Anything else? <span class="text-gray-500 font-normal text-sm">(optional)</span></legend>
                    <textarea id="notes" x-model="form.notes" rows="3" maxlength="2000" placeholder="Traveling with a group, need to sync with a teammate's pickup, etc." class="{{ $input }} resize-y"></textarea>
                </fieldset>

                <div class="space-y-6">
                    <div class="bg-[#0a0a0a] border-l-2 border-orange-500 rounded-r-xl px-5 py-4">
                        <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-1">Before you submit</p>
                        <p class="text-gray-400 text-sm leading-relaxed">Each shuttle date runs once a minimum number of seats is booked. If a date doesn't fill up, we'll cancel that run and email everyone booked in time to sort out another way up.</p>
                    </div>
                    <p x-show="submitError" x-text="submitError" class="text-red-400 text-sm" style="display: none;"></p>
                    <button type="submit" :disabled="submitting"
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                        x-text="submitting ? 'Sending…' : 'Send RSVP'">
                        Send RSVP
                    </button>
                </div>
            </form>

            {{-- Confirmation --}}
            <div x-show="submitted" style="display: none;">
                <div class="w-12 h-12 rounded-full bg-green-500/10 text-green-400 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">You're on the list</h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">We've got your shuttle RSVP. We'll confirm your final pickup point and schedule by email before race week. Questions in the meantime? Email <a href="mailto:info@ricon.ph" class="text-orange-500 underline underline-offset-2">info@ricon.ph</a>.</p>
                <dl class="bg-[#0a0a0a] border border-white/10 rounded-xl px-5 py-4 text-sm grid grid-cols-[auto_1fr] gap-x-6 gap-y-2">
                    <template x-for="[k, v] in summary()" :key="k">
                        <div class="contents">
                            <dt class="text-gray-500" x-text="k"></dt>
                            <dd class="text-gray-200" x-text="v"></dd>
                        </div>
                    </template>
                </dl>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function shuttleRsvp() {
        return {
            form: { full_name: '', email: '', mobile_number: '', distance: '', seats: 1, shuttle_date: '', return_trip: false, pickup_point: '', notes: '' },
            dateLabels: @json(\App\Models\ShuttleRsvp::SHUTTLE_DATES),
            maxSeats: {{ \App\Models\ShuttleRsvp::MAX_SEATS }},
            errors: {},
            submitError: '',
            submitting: false,
            submitted: false,

            validate() {
                const f = this.form;
                const seats = Number(f.seats);
                this.errors = {
                    full_name: !f.full_name.trim(),
                    email: !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.trim()),
                    mobile_number: f.mobile_number.trim().length < 7,
                    distance: !f.distance,
                    seats: !(Number.isInteger(seats) && seats >= 1 && seats <= this.maxSeats),
                    shuttle_date: !f.shuttle_date,
                    pickup_point: !f.pickup_point,
                };
                return !Object.values(this.errors).some(Boolean);
            },

            summary() {
                const f = this.form;
                return [
                    ['Name', f.full_name],
                    ['Distance', f.distance],
                    ['Seats', String(f.seats)],
                    ['Date', this.dateLabels[f.shuttle_date] ?? f.shuttle_date],
                    ['Pickup', f.pickup_point],
                    ['Return trip', f.return_trip ? 'Yes' : 'No'],
                ];
            },

            async submit() {
                this.submitError = '';
                if (!this.validate()) {
                    this.$nextTick(() => this.$el.querySelector('.\\!border-red-500')?.focus());
                    return;
                }

                this.submitting = true;
                try {
                    const res = await fetch("{{ route('shuttle.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({ ...this.form, seats: Number(this.form.seats) }),
                    });

                    if (res.status === 429) {
                        this.submitError = 'Too many attempts. Please wait a minute and try again.';
                    } else if (res.status === 422) {
                        const json = await res.json();
                        this.submitError = json.errors ? Object.values(json.errors)[0][0] : 'Please check your entries and try again.';
                    } else if (!res.ok) {
                        this.submitError = 'Something went wrong. Please try again.';
                    } else {
                        this.submitted = true;
                        window.scrollTo({ top: this.$el.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth' });
                    }
                } catch (e) {
                    this.submitError = 'Something went wrong. Please check your connection and try again.';
                } finally {
                    this.submitting = false;
                }
            },
        };
    }
</script>
@endpush

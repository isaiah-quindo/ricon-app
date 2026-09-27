@extends('layouts.public')
@section('title', 'Volunteer Membership')
@section('og_title', 'Volunteer Membership — RICON')
@section('og_description', "You've been shortlisted for the RiCON volunteer crew. Pick your orientation date and settle your membership fee to lock in your spot.")

@use('App\Models\VolunteerMembership')

@section('content')
@php
$input = 'w-full bg-[#0a0a0a] border border-white/10 rounded-lg text-white text-sm px-4 py-3 placeholder-gray-600 focus:border-orange-500 focus:ring-0 focus:outline-none transition-colors';
$label = 'block text-sm font-medium text-gray-300 mb-1.5';
$fee = number_format(VolunteerMembership::FEE);

$inclusions = [
    'Official RiCON Volunteer Shirt',
    'Meals & snacks during the event',
    'Sponsor discounts and vouchers',
    'Race credit/voucher toward a future RiCON event',
    'Race day insurance coverage',
];
@endphp

<div x-data="volunteerMembership()">

{{-- ======================================================== --}}
{{-- COVER + HERO --}}
{{-- ======================================================== --}}
<div class="relative w-full overflow-hidden pt-16" style="height:clamp(360px,58vw,620px);">
    <img src="{{ asset('images/volunteer/membership-cover.jpg') }}" alt="RiCON trail crew on the Great Cordillera 100 ridge"
        class="w-full h-full object-cover" style="object-position:center 68%;">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0a0a0a]/10 to-[#0a0a0a]"></div>
</div>

<section class="bg-[#0a0a0a]">
    <div class="mx-auto px-6 pt-14 pb-8 text-center" style="max-width:880px;">
        <span class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/35 text-orange-500 font-semibold text-sm px-4 py-1.5 rounded-full mb-5">
            🎉 You've Been Shortlisted
        </span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Congratulations!</h1>
        <p class="text-gray-400 text-lg max-w-xl mx-auto">You're one step closer to joining the crew behind The Great Cordillera 100. Pick your orientation date and settle your membership fee to lock in your spot.</p>
        <a href="{{ route('programs.volunteer') }}" class="inline-block mt-5 text-orange-500 font-semibold text-sm border-b border-orange-500/40 pb-0.5 hover:text-orange-400 transition-colors">
            About the Volunteer Program
        </a>
    </div>
</section>

<div class="bg-[#0a0a0a] pb-20">
<div class="mx-auto px-6" style="max-width:880px;">

    {{-- ======================================================== --}}
    {{-- STEP 1: ORIENTATION --}}
    {{-- ======================================================== --}}
    <section id="orientation" class="py-9 border-t border-white/10">
        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Step 1</p>
        <h2 class="text-2xl font-bold text-white mb-1.5">Choose Your Orientation Date</h2>
        <p class="text-gray-400 text-sm mb-6 max-w-2xl">Attendance is required before race weekend. Pick whichever date works with your schedule. Both cover the same material.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" role="radiogroup">
            @foreach(VolunteerMembership::ORIENTATION_DATES as $value => $dateLabel)
            @php $date = \Carbon\Carbon::parse($value); @endphp
            <label class="relative rounded-2xl border-[1.5px] p-5 cursor-pointer transition-colors"
                :class="form.orientation_date === '{{ $value }}' ? 'border-orange-600 bg-orange-500/[0.07]' : 'border-white/10 bg-[#161616] hover:border-orange-500/40'">
                <input type="radio" name="orientation_card" value="{{ $value }}" x-model="form.orientation_date" class="sr-only">
                <span class="w-5 h-5 rounded-full border-2 inline-flex items-center justify-center mb-3"
                    :class="form.orientation_date === '{{ $value }}' ? 'border-orange-600' : 'border-white/20'">
                    <span x-show="form.orientation_date === '{{ $value }}'" class="w-2.5 h-2.5 rounded-full bg-orange-600"></span>
                </span>
                <span class="block text-xl font-bold text-white" style="font-family:'Kufam',sans-serif;">{{ $date->format('F j, Y') }}</span>
                <span class="block text-gray-500 text-sm mt-1">{{ $date->format('l') }} &middot; {{ VolunteerMembership::ORIENTATION_TIME }}</span>
            </label>
            @endforeach
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- STEP 2: FEE --}}
    {{-- ======================================================== --}}
    <section id="fee" class="py-9 border-t border-white/10">
        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Step 2</p>
        <h2 class="text-2xl font-bold text-white mb-1.5">Volunteer Membership Fee</h2>
        <p class="text-gray-400 text-sm mb-6 max-w-2xl">A one-time fee to activate your volunteer standing with RiCON.</p>

        <div class="bg-[#161616] border border-white/10 rounded-2xl p-7">
            <div class="flex items-baseline justify-between flex-wrap gap-2 mb-1.5">
                <span class="text-4xl font-extrabold text-orange-500" style="font-family:'Kufam',sans-serif;">&#8369;{{ $fee }}</span>
                <span class="text-xs font-semibold text-emerald-400 bg-emerald-400/10 border border-emerald-400/30 px-2.5 py-1 rounded-full">Valid for 2 years</span>
            </div>
            <p class="text-gray-400 text-sm mb-6">This isn't a one-race pass. One membership keeps you part of the RiCON crew for two years, welcome at any race we run in that window, not just this one.</p>
            <ul class="grid gap-3">
                @foreach($inclusions as $item)
                <li class="flex items-start gap-2.5 text-[15px] text-gray-100">
                    <span class="flex-none w-5 h-5 mt-0.5 rounded-full bg-emerald-400/15 text-emerald-400 flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    </span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
            <div class="mt-6 pt-6 border-t border-dashed border-white/10 text-gray-400 text-sm">
                <strong class="text-white">Why it's worth it:</strong> volunteers get a view of the race no runner does. The behind-the-scenes setup, the aid station rhythm, the radio chatter that keeps 100 miles of trail running on time. You're not just working the event, you're part of how it happens.
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- STEP 3: PAYMENT --}}
    {{-- ======================================================== --}}
    <section id="payment" class="py-9 border-t border-white/10">
        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Step 3</p>
        <h2 class="text-2xl font-bold text-white mb-1.5">Pay via GCash</h2>
        <p class="text-gray-400 text-sm mb-6 max-w-2xl">Scan the QR code below with your GCash app, send &#8369;{{ $fee }}, then log your reference number in the form.</p>

        <div class="grid grid-cols-1 md:grid-cols-[260px_1fr] gap-7">
            <div class="bg-white rounded-2xl p-4 text-center border border-white/10 self-start">
                <img src="{{ asset('images/volunteer/membership-gcash-qr.jpg') }}" alt="GCash QR code" class="rounded-lg w-full">
                <p class="mt-3 font-bold text-gray-900 text-sm" style="font-family:'Kufam',sans-serif;">InstaPay / GCash</p>
                <p class="text-gray-500 text-xs mt-0.5">Scan to pay &#8369;{{ $fee }}</p>
            </div>
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 self-start">
                <ol class="list-decimal pl-5 text-gray-400 text-sm grid gap-2 marker:text-orange-500 marker:font-bold">
                    <li>Open your GCash app and tap <strong class="text-white">Scan QR</strong>.</li>
                    <li>Scan the code and enter <strong class="text-white">&#8369;{{ number_format(VolunteerMembership::FEE, 2) }}</strong> exactly.</li>
                    <li>Confirm the send. Your GCash app will show a Reference Number.</li>
                    <li>Copy that reference number into the form below.</li>
                </ol>
            </div>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- STEP 4: CONFIRM --}}
    {{-- ======================================================== --}}
    <section id="confirm-form" class="py-9 border-t border-white/10">
        <p class="text-orange-500 text-xs font-bold uppercase tracking-wider mb-2">Step 4</p>
        <h2 class="text-2xl font-bold text-white mb-1.5">Confirm Your Membership</h2>
        <p class="text-gray-400 text-sm mb-6 max-w-2xl">Submit your details once payment is sent. This locks in your orientation slot.</p>

        <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 md:p-7">
            <form x-show="!submitted" @submit.prevent="submit()" novalidate class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="{{ $label }}">Full name <span class="text-orange-500">*</span></label>
                        <input type="text" id="full_name" x-model="form.full_name" autocomplete="name" class="{{ $input }}" :class="errors.full_name && '!border-red-500'">
                        <p x-show="errors.full_name" class="text-red-400 text-xs mt-1.5">Please enter your name.</p>
                    </div>
                    <div>
                        <label for="email" class="{{ $label }}">Email address <span class="text-orange-500">*</span></label>
                        <input type="email" id="email" x-model="form.email" autocomplete="email" class="{{ $input }}" :class="errors.email && '!border-red-500'">
                        <p x-show="errors.email" class="text-red-400 text-xs mt-1.5">Please enter a valid email.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="mobile_number" class="{{ $label }}">Mobile number <span class="text-orange-500">*</span></label>
                        <input type="tel" id="mobile_number" x-model="form.mobile_number" autocomplete="tel" placeholder="09XX XXX XXXX" class="{{ $input }}" :class="errors.mobile_number && '!border-red-500'">
                        <p x-show="errors.mobile_number" class="text-red-400 text-xs mt-1.5">Please enter a mobile number.</p>
                    </div>
                    <div>
                        <label for="orientation_date" class="{{ $label }}">Orientation date <span class="text-orange-500">*</span></label>
                        <select id="orientation_date" x-model="form.orientation_date" class="{{ $input }}" :class="errors.orientation_date && '!border-red-500'">
                            <option value="" disabled>Select a date</option>
                            @foreach(VolunteerMembership::ORIENTATION_DATES as $value => $dateLabel)
                            <option value="{{ $value }}">{{ \Carbon\Carbon::parse($value)->format('F j, Y') }} &middot; {{ VolunteerMembership::ORIENTATION_TIME }}</option>
                            @endforeach
                        </select>
                        <p x-show="errors.orientation_date" class="text-red-400 text-xs mt-1.5">Please choose an orientation date.</p>
                    </div>
                </div>
                <div>
                    <label for="gcash_reference" class="{{ $label }}">GCash reference number <span class="text-orange-500">*</span></label>
                    <input type="text" id="gcash_reference" x-model="form.gcash_reference" placeholder="e.g. 1234 5678 9012" class="{{ $input }}" :class="errors.gcash_reference && '!border-red-500'">
                    <p x-show="errors.gcash_reference" class="text-red-400 text-xs mt-1.5">Please enter the reference number from your GCash receipt.</p>
                </div>

                <label class="flex items-start gap-3 cursor-pointer pt-2">
                    <input type="checkbox" x-model="form.consent" class="mt-0.5 rounded border-white/20 bg-[#0a0a0a] text-orange-600 focus:ring-orange-500 focus:ring-offset-0">
                    <span class="text-sm" :class="errors.consent ? 'text-red-400' : 'text-gray-400'">I confirm I've sent &#8369;{{ $fee }} via GCash and the reference number above is accurate. I understand this membership is valid for 2 years from date of payment.</span>
                </label>

                <p x-show="submitError" x-text="submitError" class="text-red-400 text-sm" style="display: none;"></p>
                <button type="submit" :disabled="submitting"
                    class="w-full py-3.5 px-4 inline-flex justify-center items-center gap-x-2 font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                    style="font-family:'Kufam',sans-serif;"
                    x-text="submitting ? 'Sending…' : 'Confirm Membership'">
                    Confirm Membership
                </button>
            </form>

            {{-- Confirmation --}}
            <div x-show="submitted" style="display: none;" class="text-center py-5">
                <div class="w-14 h-14 rounded-full bg-emerald-400/15 text-emerald-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">You're All Set</h3>
                <p class="text-gray-400 text-sm max-w-md mx-auto">We've received your membership details and will verify your GCash payment. See you at orientation on <span class="text-white font-semibold" x-text="dateLabels[form.orientation_date]"></span>. Bring a valid ID and your race-day energy.</p>
            </div>
        </div>

        <div class="bg-[#161616] border border-white/10 rounded-lg px-4 py-3.5 mt-6 text-[13px] text-gray-500">
            <strong class="text-gray-400">Transport note:</strong> RiCON covers transport from the event venue to your assigned deployment area only. Getting yourself to and from the venue is on you, so plan your own trip there.
        </div>
    </section>

</div>
</div>

</div>
@endsection

@push('scripts')
<script>
    function volunteerMembership() {
        const dateLabels = @json(\App\Models\VolunteerMembership::ORIENTATION_DATES);
        // Invite links can preselect a date, e.g. ?orientation=2026-10-10
        const preselected = new URLSearchParams(window.location.search).get('orientation');

        return {
            form: {
                full_name: '', email: '', mobile_number: '', gcash_reference: '', consent: false,
                orientation_date: preselected in dateLabels ? preselected : '',
            },
            dateLabels,
            errors: {},
            submitError: '',
            submitting: false,
            submitted: false,

            validate() {
                const f = this.form;
                this.errors = {
                    full_name: !f.full_name.trim(),
                    email: !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.trim()),
                    mobile_number: f.mobile_number.trim().length < 7,
                    orientation_date: !f.orientation_date,
                    gcash_reference: f.gcash_reference.trim().length < 6,
                    consent: !f.consent,
                };
                return !Object.values(this.errors).some(Boolean);
            },

            async submit() {
                this.submitError = '';
                if (!this.validate()) {
                    this.submitError = this.errors.consent && Object.values(this.errors).filter(Boolean).length === 1
                        ? 'Please tick the confirmation box before submitting.'
                        : 'Please fill in all required fields.';
                    this.$nextTick(() => document.querySelector('#confirm-form .\\!border-red-500')?.focus());
                    return;
                }

                this.submitting = true;
                try {
                    const res = await fetch("{{ route('programs.volunteer.membership.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? "{{ csrf_token() }}",
                        },
                        body: JSON.stringify(this.form),
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
                        const panel = document.getElementById('confirm-form');
                        window.scrollTo({ top: panel.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });
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

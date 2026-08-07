@extends('layouts.admin')
@section('title', 'Registration Detail')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.registrations.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Registrations
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column: Participant details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Header card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $registration->first_name }} {{ $registration->last_name }}
                        </h2>
                        @include('admin.registrations._status_badge', ['status' => $registration->status])
                    </div>
                    <p class="text-sm text-gray-500">{{ $registration->email }}</p>
                </div>
                @if($registration->bib_number)
                <div class="flex flex-col items-center">
                    <div class="w-auto h-16 bg-indigo-600 rounded-xl flex items-center justify-center px-4">
                        <span class="text-white font-bold text-xl">{{ $registration->formatted_bib }}</span>
                    </div>
                    <span class="text-xs text-gray-400 mt-1">Bib #</span>
                </div>
                @endif
            </div>

            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Category</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->raceCategory?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Sex</p>
                    <p class="text-sm font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $registration->sex) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Shirt Size</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->shirt_size }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Mobile</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->mobile_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Birthdate</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->birthdate->format('M j, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Registered</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->created_at->format('M j, Y g:i A') }}</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nationality</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->nationality ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Club / Affiliation</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->affiliation ?: '—' }}</p>
                </div>
                <div class="col-span-2 sm:col-span-3">
                    <p class="text-xs text-gray-400 mb-0.5">Address</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->address }}</p>
                </div>
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Emergency Contact
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Name</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->emergency_contact_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Phone</p>
                    <p class="text-sm font-medium text-gray-800">{{ $registration->emergency_contact_number }}</p>
                </div>
            </div>
        </div>

        {{-- Payment Proof --}}
        @if($registration->paymentProof)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Proof of Payment
            </h3>
            <div class="flex flex-wrap gap-4 items-start">
                <div class="flex-1 min-w-0">
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Payment Method</p>
                            <p class="text-sm font-medium text-gray-800">{{ $registration->paymentProof->payment_method ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Proof Status</p>
                            @include('admin.registrations._status_badge', ['status' => $registration->paymentProof->status])
                        </div>
                        @if($registration->paymentProof->verified_at)
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Verified At</p>
                            <p class="text-sm font-medium text-gray-800">{{ $registration->paymentProof->verified_at->format('M j, Y g:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                <a href="{{ Storage::disk('s3')->url($registration->paymentProof->image_path) }}"
                    target="_blank"
                    class="block rounded-lg overflow-hidden border border-gray-200 hover:border-indigo-300 transition-colors flex-shrink-0">
                    <img src="{{ Storage::disk('s3')->url($registration->paymentProof->image_path) }}"
                        alt="Payment proof"
                        class="w-40 h-40 object-cover" />
                </a>
            </div>
        </div>
        @endif

        {{-- Admin Notes --}}
        @if($registration->admin_notes)
        <div class="bg-amber-50 rounded-xl border border-amber-200 p-6">
            <h3 class="text-sm font-semibold text-amber-800 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Admin Notes
            </h3>
            <p class="text-sm text-amber-900">{{ $registration->admin_notes }}</p>
        </div>
        @endif

    </div>

    {{-- Right column: Actions --}}
    <div class="space-y-4">

        {{-- Group panel --}}
        @if($registration->group?->isGroup())
        @php
            $group = $registration->group;
            $members = $group->registrations->sortBy(fn($m) => $m->last_name . $m->first_name);
            $pendingCount = $members->where('status', '!=', 'approved')->count();
        @endphp
        <div class="bg-amber-50 rounded-xl border border-amber-200 p-5">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div>
                    <h3 class="text-sm font-semibold text-amber-900">Group Registration</h3>
                    <p class="text-xs font-mono text-amber-700 mt-0.5">{{ $group->reference_code }}</p>
                </div>
                <span class="px-2 py-0.5 bg-amber-200 text-amber-900 text-xs font-semibold rounded">
                    {{ $group->participant_count }} people
                </span>
            </div>

            <dl class="space-y-1.5 mb-4 pb-4 border-b border-amber-200">
                <div class="flex justify-between">
                    <dt class="text-xs text-amber-700">Group Subtotal</dt>
                    <dd class="text-xs font-semibold text-amber-900">₱{{ number_format($group->subtotal, 2) }}</dd>
                </div>
                @if($group->discount_source === 'group')
                <div class="flex justify-between">
                    <dt class="text-xs text-amber-700">Group Discount ({{ rtrim(rtrim(number_format($group->group_discount_percentage, 2), '0'), '.') }}%)</dt>
                    <dd class="text-xs font-semibold text-green-700">−₱{{ number_format($group->discount_total, 2) }}</dd>
                </div>
                @elseif($group->discount_source === 'code')
                <div class="flex justify-between">
                    <dt class="text-xs text-amber-700">Code <span class="font-mono">{{ $group->discountCode?->code }}</span></dt>
                    <dd class="text-xs font-semibold text-green-700">−₱{{ number_format($group->discount_total, 2) }}</dd>
                </div>
                @endif
                <div class="flex justify-between pt-1.5 border-t border-amber-200">
                    <dt class="text-xs font-semibold text-amber-800">Total Paid</dt>
                    <dd class="text-xs font-bold text-amber-900">₱{{ number_format($group->total_due, 2) }}</dd>
                </div>
            </dl>

            <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider mb-2">Members</p>
            <ul class="space-y-1.5 mb-4">
                @foreach($members as $member)
                <li class="flex items-center justify-between gap-2 text-xs">
                    @if($member->is($registration))
                    <span class="font-semibold text-amber-900 truncate">
                        {{ $member->first_name }} {{ $member->last_name }}
                        <span class="text-amber-600 font-normal">(viewing)</span>
                    </span>
                    @else
                    <a href="{{ route('admin.registrations.show', $member) }}"
                        class="text-amber-800 hover:text-amber-900 hover:underline truncate">
                        {{ $member->first_name }} {{ $member->last_name }}
                    </a>
                    @endif
                    <span class="flex items-center gap-1.5 flex-shrink-0">
                        <span class="text-amber-600">{{ $member->raceCategory?->name }}</span>
                        @include('admin.registrations._status_badge', ['status' => $member->status])
                    </span>
                </li>
                @endforeach
            </ul>

            {{-- Payment and bulk approval live on the group page, so there is one place
                 where the transaction is settled. --}}
            <a href="{{ route('admin.registration-groups.show', $group) }}"
                class="w-full px-4 py-2.5 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors flex items-center justify-center gap-2">
                Open group transaction
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <div class="mt-3 text-xs text-amber-700 space-y-1">
                <p>{{ $group->participant_count - $pendingCount }} of {{ $group->participant_count }} approved.</p>
                <p>All members share one proof of payment. Rejecting one member does not affect the others.</p>
            </div>
        </div>
        @endif

        {{-- Approve action --}}
        @if(in_array($registration->status, ['pending', 'payment_submitted']))
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Approve Registration</h3>
            @if($registration->isBlockedByGroupPayment())
            {{-- A group is settled by one transfer; no member is approved before it lands. --}}
            <p class="text-xs text-gray-500 mb-4">
                This runner is part of a group. Record the group's payment before approving anyone in it.
            </p>
            <button type="button" disabled
                class="w-full px-4 py-2.5 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
                Approve
            </button>
            <a href="{{ route('admin.registration-groups.show', $registration->group) }}"
                class="block text-center text-xs text-amber-700 hover:text-amber-800 font-medium mt-2">
                Go to {{ $registration->group->reference_code }} to record it
            </a>
            @else
            <p class="text-xs text-gray-500 mb-4">
                Approving will assign a bib number and mark this registration as confirmed.
            </p>
            <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
                @csrf
                <button type="submit"
                    onclick="return confirm('Approve this registration?')"
                    class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Approve
                </button>
            </form>
            @endif
        </div>

        {{-- Reject action --}}
        <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Reject Registration</h3>
            <p class="text-xs text-gray-500 mb-4">
                Provide a reason so the participant knows why they were not approved.
            </p>
            <button @click="open = !open"
                class="w-full px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                Reject
            </button>
            <div x-show="open" x-transition class="mt-4">
                <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Reason <span class="text-red-500">*</span></label>
                        <textarea name="admin_notes" rows="3" required
                            class="w-full rounded-lg border border-gray-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                            placeholder="Explain the reason for rejection..."></textarea>
                        @error('admin_notes')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Confirm Rejection
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Update Bib Number --}}
        <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Bib Number</h3>
            @if($registration->bib_number)
            <p class="text-2xl font-bold text-indigo-600 mb-3">{{ $registration->formatted_bib }}</p>
            @else
            <p class="text-xs text-gray-400 mb-3">Not yet assigned</p>
            @endif
            <button @click="open = !open"
                class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                {{ $registration->bib_number ? 'Update Bib Number' : 'Assign Bib Number' }}
            </button>
            <div x-show="open" x-transition class="mt-4">
                <form method="POST" action="{{ route('admin.registrations.updateBib', $registration) }}">
                    @csrf
                    @method('PATCH')
                    <div class="flex gap-2">
                        <input type="number" name="bib_number" min="1"
                            value="{{ $registration->bib_number }}"
                            required
                            class="flex-1 rounded-lg border border-gray-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Enter bib #" />
                        <button type="submit"
                            class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Save
                        </button>
                    </div>
                    @error('bib_number')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>

        {{-- Resend Confirmation Email --}}
        @if($registration->status === 'approved')
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Resend Confirmation Email</h3>
            <p class="text-xs text-gray-500 mb-4">
                Send the approval email again to <span class="font-medium text-gray-700">{{ $registration->email }}</span>.
                Use this if the participant says they did not receive the original email.
            </p>
            <form method="POST" action="{{ route('admin.registrations.resendEmail', $registration) }}">
                @csrf
                <button type="submit"
                    onclick="return confirm('Resend confirmation email to {{ $registration->email }}?')"
                    class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 11H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                    Resend Email
                </button>
            </form>
        </div>
        @endif

        {{-- Race info --}}
        @if($registration->raceCategory)
        <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-5">
            <h3 class="text-sm font-semibold text-indigo-800 mb-3">Race Category</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-xs text-indigo-500">Distance</span>
                    <span class="text-xs font-semibold text-indigo-900">{{ $registration->raceCategory->distance_km }} km</span>
                </div>
                @if($registration->raceCategory->elevation_m)
                <div class="flex justify-between">
                    <span class="text-xs text-indigo-500">Elevation</span>
                    <span class="text-xs font-semibold text-indigo-900">{{ $registration->raceCategory->elevation_m }} m</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-xs text-indigo-500">Base Fee</span>
                    <span class="text-xs font-semibold text-indigo-900">₱{{ number_format($registration->raceCategory->price, 2) }}</span>
                </div>
                @if($registration->discountCode)
                <div class="flex justify-between">
                    <span class="text-xs text-indigo-500">Discount Code</span>
                    <span class="text-xs font-mono font-semibold text-indigo-900">{{ $registration->discountCode->code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-indigo-500">Discount Amount</span>
                    <span class="text-xs font-semibold text-green-700">−₱{{ number_format($registration->discount_amount, 2) }}</span>
                </div>
                @elseif($registration->group?->discount_source === 'group' && $registration->discount_amount)
                <div class="flex justify-between">
                    <span class="text-xs text-indigo-500">Group Discount</span>
                    <span class="text-xs font-semibold text-green-700">
                        {{ rtrim(rtrim(number_format($registration->group->group_discount_percentage, 2), '0'), '.') }}%
                        · −₱{{ number_format($registration->discount_amount, 2) }}
                    </span>
                </div>
                @endif
                <div class="flex justify-between pt-2 border-t border-indigo-200">
                    <span class="text-xs font-semibold text-indigo-700">Paid</span>
                    <span class="text-xs font-bold text-indigo-900">₱{{ number_format($registration->price_paid ?? $registration->raceCategory->price, 2) }}</span>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
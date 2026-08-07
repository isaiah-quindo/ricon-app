@extends('layouts.admin')
@section('title', 'Group ' . $group->reference_code)

@section('content')

@php
    $approved = $group->approvedCount();
    $pending  = $group->pendingCount();   // excludes rejected, which are already decided
    $shortfall = $group->paymentShortfall();
    $proofPath = $group->registrations->first()?->paymentProof?->image_path;
@endphp

<div class="mb-4">
    <a href="{{ route('admin.registration-groups.index') }}"
        class="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        All groups
    </a>
</div>

{{-- Header --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold font-mono text-gray-900">{{ $group->reference_code }}</h1>
                @include('admin.registration_groups._payment_badge', ['status' => $group->payment_status])
            </div>
            <p class="text-sm text-gray-500 mt-1">
                {{ $group->participant_count }} participants ·
                Submitted {{ $group->created_at->format('F j, Y \a\t g:i A') }}
                ({{ $group->created_at->diffForHumans() }})
            </p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Total due</p>
            <p class="text-2xl font-bold text-gray-900">₱{{ number_format($group->total_due, 2) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">

    {{-- Left: who, how, breakdown --}}
    <div class="lg:col-span-2 flex flex-col gap-4">

        {{-- Who booked it --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Who made this registration</h2>
            @if($group->organizer_name)
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-xs text-gray-400 mb-0.5">Name</dt>
                    <dd class="font-medium text-gray-800">{{ $group->organizer_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 mb-0.5">Team / Club</dt>
                    <dd class="font-medium text-gray-800">{{ $group->organizer_team ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 mb-0.5">Email</dt>
                    <dd class="font-medium text-gray-800 break-all">
                        <a href="mailto:{{ $group->organizer_email }}" class="text-indigo-600 hover:underline">{{ $group->organizer_email }}</a>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 mb-0.5">Mobile</dt>
                    <dd class="font-medium text-gray-800">{{ $group->organizer_mobile }}</dd>
                </div>
            </dl>
            @else
            <p class="text-sm text-gray-500">
                No organizer recorded. This group predates organizer capture; the contact is
                <span class="font-medium text-gray-700">{{ $group->leader_email }}</span>.
            </p>
            @endif
        </div>

        {{-- Price breakdown --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-800">Breakdown</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Participant</th>
                            <th class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Paid</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($group->registrations->sortBy(fn ($r) => $r->last_name . $r->first_name) as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.registrations.show', $member) }}"
                                    class="font-medium text-indigo-600 hover:underline">
                                    {{ $member->first_name }} {{ $member->last_name }}
                                </a>
                                <span class="block text-xs text-gray-400">{{ $member->email }}</span>
                            </td>
                            <td class="px-6 py-3 text-gray-700">
                                {{ $member->raceCategory?->name ?? '—' }}
                                @if($member->bib_number)
                                <span class="block text-xs text-indigo-600 font-semibold">{{ $member->formatted_bib }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @include('admin.registrations._status_badge', ['status' => $member->status])
                            </td>
                            <td class="px-6 py-3 text-right text-gray-700">
                                ₱{{ number_format($member->raceCategory?->price ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-3 text-right text-green-600">
                                {{ $member->discount_amount ? '−₱' . number_format($member->discount_amount, 2) : '—' }}
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-gray-900">
                                ₱{{ number_format($member->price_paid, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="5" class="px-6 py-2.5 text-right text-gray-500">Subtotal</td>
                            <td class="px-6 py-2.5 text-right font-medium text-gray-800">₱{{ number_format($group->subtotal, 2) }}</td>
                        </tr>
                        @if($group->discount_total > 0)
                        <tr>
                            <td colspan="5" class="px-6 py-2.5 text-right text-gray-500">
                                @if($group->discount_source === 'group')
                                    Group discount ({{ rtrim(rtrim(number_format($group->group_discount_percentage, 2), '0'), '.') }}%)
                                @else
                                    Discount code <span class="font-mono">{{ $group->discountCode?->code }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-2.5 text-right font-medium text-green-600">−₱{{ number_format($group->discount_total, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="border-t border-gray-200">
                            <td colspan="5" class="px-6 py-3 text-right font-semibold text-gray-900">Total due</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-900">₱{{ number_format($group->total_due, 2) }}</td>
                        </tr>
                        @if($group->amount_received !== null)
                        <tr>
                            <td colspan="5" class="px-6 py-2.5 text-right text-gray-500">Amount received</td>
                            <td class="px-6 py-2.5 text-right font-medium text-gray-800">₱{{ number_format($group->amount_received, 2) }}</td>
                        </tr>
                        @if($group->hasPaymentDiscrepancy())
                        <tr>
                            <td colspan="5" class="px-6 py-2.5 text-right font-semibold text-amber-700">
                                {{ $shortfall > 0 ? 'Short by' : 'Overpaid by' }}
                            </td>
                            <td class="px-6 py-2.5 text-right font-bold text-amber-700">₱{{ number_format(abs($shortfall), 2) }}</td>
                        </tr>
                        @endif
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Shared proof --}}
        @if($proofPath)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-1">Proof of payment</h2>
            <p class="text-xs text-gray-500 mb-4">One receipt covers the whole group.</p>
            <a href="{{ Storage::disk('s3')->url($proofPath) }}" target="_blank"
                class="block border border-gray-200 rounded-lg overflow-hidden hover:border-indigo-300 transition-colors">
                <img src="{{ Storage::disk('s3')->url($proofPath) }}" alt="Proof of payment"
                    class="w-full max-h-96 object-contain bg-gray-50">
            </a>
        </div>
        @endif
    </div>

    {{-- Right: payment actions --}}
    <div class="flex flex-col gap-4">

        {{-- How it was paid --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Payment</h3>
            <dl class="space-y-2.5 text-sm">
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Method</dt>
                    <dd class="font-medium text-gray-800">{{ $group->payment_method ?: 'Not specified' }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Status</dt>
                    <dd>@include('admin.registration_groups._payment_badge', ['status' => $group->payment_status])</dd>
                </div>
                @if($group->payment_reference)
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Reference</dt>
                    <dd class="font-mono text-xs text-gray-800 text-right break-all">{{ $group->payment_reference }}</dd>
                </div>
                @endif
                @if($group->verified_at)
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Verified</dt>
                    <dd class="text-right text-gray-800">
                        {{ $group->verified_at->format('M j, Y g:i A') }}
                        @if($group->verifier)
                        <span class="block text-xs text-gray-400">by {{ $group->verifier->name }}</span>
                        @endif
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Record the payment --}}
        @if($group->payment_status !== 'verified')
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Record payment</h3>
            <p class="text-xs text-gray-500 mb-4">
                Marks the group transfer received and verifies every member's copy of the receipt.
            </p>
            <form method="POST" action="{{ route('admin.registration-groups.markPaid', $group) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Amount received <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="amount_received" required
                        value="{{ old('amount_received', $group->total_due) }}"
                        class="w-full rounded-lg border border-gray-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('amount_received')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Bank / transaction reference</label>
                    <input type="text" name="payment_reference" value="{{ old('payment_reference') }}"
                        placeholder="Optional"
                        class="w-full rounded-lg border border-gray-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <button type="submit"
                    class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Mark as paid
                </button>
            </form>
        </div>

        <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Reject payment</h3>
            <button @click="open = !open"
                class="w-full px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                Reject
            </button>
            <div x-show="open" x-cloak x-transition class="mt-4">
                <form method="POST" action="{{ route('admin.registration-groups.rejectPayment', $group) }}">
                    @csrf
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Reason <span class="text-red-500">*</span></label>
                    <textarea name="admin_notes" rows="3" required
                        class="w-full rounded-lg border border-gray-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                        placeholder="Why was this payment rejected?"></textarea>
                    <button type="submit"
                        class="w-full mt-3 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Confirm rejection
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Approve members --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Approvals</h3>
            <p class="text-xs text-gray-500 mb-4">{{ $approved }} of {{ $group->participant_count }} approved.</p>
            @if($pending > 0)
                @if($group->isPaymentVerified())
                <x-confirm-modal
                    :action="route('admin.registration-groups.approveAll', $group)"
                    trigger="Approve all {{ $pending }} remaining"
                    title="Approve {{ $pending }} {{ Str::plural('participant', $pending) }}?"
                    confirm-label="Approve {{ $pending }}">
                    <ul class="text-sm text-gray-500 mt-2 space-y-1">
                        <li class="flex items-start gap-2">
                            <span class="text-gray-300">&bull;</span>
                            Each is assigned a bib number.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-gray-300">&bull;</span>
                            All {{ $pending }} are emailed their confirmation immediately.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-gray-300">&bull;</span>
                            Group <span class="font-mono text-gray-700 whitespace-nowrap">{{ $group->reference_code }}</span>,
                            organized by {{ $group->organizer_name ?? $group->leader_email }}.
                        </li>
                    </ul>
                </x-confirm-modal>
                @else
                {{-- Approval issues bibs and emails everyone, so the money comes first. --}}
                <button type="button" disabled
                    class="w-full px-4 py-2.5 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
                    Approve all {{ $pending }} remaining
                </button>
                <p class="text-xs text-amber-600 mt-2">
                    Record the payment first. Approving assigns bib numbers and emails every participant.
                </p>
                @endif
            @else
            <p class="text-xs text-green-700 font-medium">Everyone is approved.</p>
            @endif
        </div>

        {{-- Organizer summary email --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Organizer summary</h3>
            @if($group->organizerNotified())
            <p class="text-xs text-gray-500 mb-4">
                Sent to <span class="font-medium text-gray-700">{{ $group->organizer_email }}</span>
                on {{ $group->organizer_notified_at->format('M j, Y g:i A') }}.
            </p>
            @else
            <p class="text-xs text-gray-500 mb-4">
                An aggregated recap goes to {{ $group->organizer_email ?? 'the organizer' }} automatically
                once every participant is approved or rejected. Participants are emailed individually as usual.
            </p>
            @endif

            @if($group->organizer_email)
            <form method="POST" action="{{ route('admin.registration-groups.resendSummary', $group) }}">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    {{ $group->organizerNotified() ? 'Resend summary' : 'Send summary now' }}
                </button>
            </form>
            @else
            <p class="text-xs text-amber-600">No organizer email on record for this group.</p>
            @endif
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Notes</h3>
            <form method="POST" action="{{ route('admin.registration-groups.updateNotes', $group) }}">
                @csrf
                <textarea name="admin_notes" rows="4"
                    class="w-full rounded-lg border border-gray-200 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder="Internal notes about this group">{{ old('admin_notes', $group->admin_notes) }}</textarea>
                <button type="submit"
                    class="w-full mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Save notes
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

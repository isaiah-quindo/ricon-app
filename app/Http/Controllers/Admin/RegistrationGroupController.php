<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationApproved;
use App\Models\Registration;
use App\Models\RegistrationGroup;
use App\Services\GroupSummaryNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Group registrations viewed as transactions: one booking, one payment, many runners.
 *
 * Individual registrations are intentionally absent here. They are parties of one with
 * their own per-person payment proof, and they live under Registrations as before.
 */
class RegistrationGroupController extends Controller
{
    /** A party of one is a plain registration, not a group transaction. */
    private function ensureIsGroup(RegistrationGroup $group): void
    {
        abort_unless($group->isGroup(), 404);
    }

    public function index(Request $request)
    {
        $groups = RegistrationGroup::query()
            ->with(['registrations:id,registration_group_id,status'])
            ->groups()
            ->when($request->payment_status, fn ($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->search, function ($q) use ($request) {
                $term = '%' . $request->search . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('reference_code', 'like', $term)
                        ->orWhere('organizer_name', 'like', $term)
                        ->orWhere('organizer_email', 'like', $term)
                        ->orWhere('organizer_team', 'like', $term)
                        ->orWhere('leader_email', 'like', $term);
                });
            })
            ->when(true, function ($q) use ($request) {
                $sortable = ['created_at', 'participant_count', 'total_due', 'payment_status'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir  = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        $totals = [
            'groups'       => RegistrationGroup::groups()->count(),
            'participants' => RegistrationGroup::groups()->sum('participant_count'),
            'awaiting'     => RegistrationGroup::groups()->where('payment_status', 'pending')->count(),
            'verified_due' => RegistrationGroup::groups()->where('payment_status', 'verified')->sum('total_due'),
        ];

        return view('admin.registration_groups.index', compact('groups', 'totals'));
    }

    public function show(RegistrationGroup $registrationGroup)
    {
        $this->ensureIsGroup($registrationGroup);

        $registrationGroup->load([
            'discountCode',
            'verifier',
            'registrations.raceCategory',
            'registrations.paymentProof',
        ]);

        return view('admin.registration_groups.show', ['group' => $registrationGroup]);
    }

    /**
     * Record that the single group transfer arrived, and mark every member's copy of
     * the shared proof verified in the same breath.
     */
    public function markPaid(Request $request, RegistrationGroup $registrationGroup)
    {
        $this->ensureIsGroup($registrationGroup);

        $data = $request->validate([
            'amount_received'   => 'required|numeric|min:0',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($registrationGroup, $data) {
            $registrationGroup->update([
                'payment_status'    => 'verified',
                'amount_received'   => $data['amount_received'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'verified_at'       => now(),
                'verified_by'       => auth()->id(),
            ]);

            // The members all share one receipt, so verifying the transfer verifies each copy.
            foreach ($registrationGroup->registrations as $registration) {
                $registration->paymentProof?->update([
                    'status'      => 'verified',
                    'verified_at' => now(),
                ]);
            }
        });

        $message = 'Payment recorded.';
        if ($registrationGroup->fresh()->hasPaymentDiscrepancy()) {
            $shortfall = $registrationGroup->fresh()->paymentShortfall();
            $message .= $shortfall > 0
                ? ' Note: ₱' . number_format($shortfall, 2) . ' short of the amount due.'
                : ' Note: ₱' . number_format(abs($shortfall), 2) . ' more than the amount due.';
        }

        return back()->with('success', $message);
    }

    public function rejectPayment(Request $request, RegistrationGroup $registrationGroup)
    {
        $this->ensureIsGroup($registrationGroup);

        $data = $request->validate(['admin_notes' => 'required|string|max:1000']);

        DB::transaction(function () use ($registrationGroup, $data) {
            $registrationGroup->update([
                'payment_status' => 'rejected',
                'admin_notes'    => $data['admin_notes'],
                'verified_at'    => null,
                'verified_by'    => null,
            ]);

            foreach ($registrationGroup->registrations as $registration) {
                $registration->paymentProof?->update(['status' => 'rejected']);
            }
        });

        return back()->with('success', 'Group payment marked as rejected.');
    }

    public function updateNotes(Request $request, RegistrationGroup $registrationGroup)
    {
        $this->ensureIsGroup($registrationGroup);

        $data = $request->validate(['admin_notes' => 'nullable|string|max:1000']);

        $registrationGroup->update(['admin_notes' => $data['admin_notes'] ?? null]);

        return back()->with('success', 'Notes saved.');
    }

    /** Re-send the organizer's aggregated recap on demand. */
    public function resendSummary(RegistrationGroup $registrationGroup)
    {
        $this->ensureIsGroup($registrationGroup);

        if (! $registrationGroup->organizer_email) {
            return back()->with('error', 'This group has no organizer email on record.');
        }

        GroupSummaryNotifier::send($registrationGroup);

        return back()->with('success', "Summary re-sent to {$registrationGroup->organizer_email}.");
    }

    /** Approve every still-pending member and assign their bibs. */
    public function approveAll(RegistrationGroup $registrationGroup)
    {
        $this->ensureIsGroup($registrationGroup);

        // Record the money before issuing bibs and emailing five people.
        if (! $registrationGroup->isPaymentVerified()) {
            return back()->with('error', 'Record the payment for this group before approving anyone.');
        }

        // Only members still awaiting a decision. A rejection is deliberate, so bulk
        // approval must not quietly overturn it.
        $pending = $registrationGroup->registrations()
            ->with(['raceCategory', 'paymentProof'])
            ->whereNotIn('status', ['approved', 'rejected'])
            ->get();

        if ($pending->isEmpty()) {
            return back()->with('success', 'Nobody in this group is awaiting approval.');
        }

        foreach ($pending as $registration) {
            $registration->status = 'approved';
            $registration->assignBibNumber();
            $registration->paymentProof?->update(['status' => 'verified', 'verified_at' => now()]);
            Mail::to($registration->email)->send(new RegistrationApproved($registration));
        }

        // Once nobody is left pending, the organizer gets one aggregated recap.
        $summarySent = GroupSummaryNotifier::sendIfComplete($registrationGroup);

        return back()->with(
            'success',
            "Approved {$pending->count()} of {$registrationGroup->participant_count} in {$registrationGroup->reference_code}."
            . ($summarySent ? " Summary emailed to {$registrationGroup->organizer_email}." : '')
        );
    }
}

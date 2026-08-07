<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use App\Models\Registration;
use App\Models\RegistrationGroup;
use App\Models\PaymentProof;
use App\Services\GroupSummaryNotifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    // List all registrations
    public function index(Request $request)
    {
        $registrations = Registration::with(['raceCategory', 'paymentProof', 'group'])
            ->tap(fn($q) => $this->applyFilters($q, $request))
            ->when(true, function ($q) use ($request) {
                $sortable = ['last_name', 'status', 'bib_number', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20);

        return view('admin.registrations.index', compact('registrations'));
    }

    // Shared by index() and export() so the CSV always matches what is on screen.
    private function applyFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->category,   fn($q) => $q->where('race_category_id', $request->category))
            ->when($request->status,     fn($q) => $q->where('status', $request->status))
            ->when($request->shirt_size, fn($q) => $q->where('shirt_size', $request->shirt_size))
            ->when($request->sex,        fn($q) => $q->where('sex', $request->sex))
            ->when($request->group, function ($q) use ($request) {
                // Either a specific group's reference code, or every grouped registration.
                return $request->group === 'any'
                    ? $q->whereHas('group', fn($g) => $g->groups())
                    : $q->whereHas('group', fn($g) => $g->where('reference_code', $request->group));
            })
            ->when($request->age_group, function ($q) use ($request) {
                $now = now();
                return match ($request->age_group) {
                    'under20' => $q->where('birthdate', '>', $now->copy()->subYears(20)),
                    '20-29'   => $q->whereBetween('birthdate', [$now->copy()->subYears(30), $now->copy()->subYears(20)]),
                    '30-39'   => $q->whereBetween('birthdate', [$now->copy()->subYears(40), $now->copy()->subYears(30)]),
                    '40-49'   => $q->whereBetween('birthdate', [$now->copy()->subYears(50), $now->copy()->subYears(40)]),
                    '50plus'  => $q->where('birthdate', '<', $now->copy()->subYears(50)),
                    default   => $q,
                };
            });
    }

    // Export filtered registrations to CSV
    public function export(Request $request)
    {
        $registrations = Registration::with(['raceCategory', 'discountCode', 'group'])
            ->tap(fn($q) => $this->applyFilters($q, $request))
            ->latest()
            ->get();

        $filename = 'registrations-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($registrations) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id', 'group_reference', 'group_size', 'race_category', 'first_name', 'last_name', 'sex',
                'email', 'mobile_number', 'birthdate', 'address', 'nationality', 'affiliation', 'shirt_size',
                'emergency_contact_name', 'emergency_contact_number',
                'bib_number', 'price_paid', 'discount_code', 'discount_amount', 'status', 'admin_notes',
                'waiver_agreed', 'terms_agreed', 'created_at', 'updated_at',
            ]);

            foreach ($registrations as $reg) {
                fputcsv($handle, [
                    $reg->id,
                    $reg->group?->reference_code ?? '',
                    $reg->group?->participant_count ?? '',
                    $reg->raceCategory->name ?? '',
                    $reg->first_name,
                    $reg->last_name,
                    $reg->sex,
                    $reg->email,
                    $reg->mobile_number,
                    $reg->birthdate?->format('Y-m-d'),
                    $reg->address,
                    $reg->nationality,
                    $reg->affiliation,
                    $reg->shirt_size,
                    $reg->emergency_contact_name,
                    $reg->emergency_contact_number,
                    $reg->formatted_bib,
                    $reg->price_paid,
                    $reg->discountCode?->code ?? '',
                    $reg->discount_amount,
                    $reg->status,
                    $reg->admin_notes,
                    $reg->waiver_agreed ? 'yes' : 'no',
                    $reg->terms_agreed  ? 'yes' : 'no',
                    $reg->created_at->toDateTimeString(),
                    $reg->updated_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    // View a single registration
    public function show(Registration $registration)
    {
        $registration->load([
            'raceCategory',
            'paymentProof',
            'discountCode',
            'group.discountCode',
            'group.registrations.raceCategory',
        ]);

        return view('admin.registrations.show', compact('registration'));
    }

    // Approve registration and assign bib number
    public function approve(Registration $registration)
    {
        // One transfer covers a whole group, so its payment has to be recorded on the
        // group before any member can be approved. Bulk approval lives on the group page.
        if ($registration->isBlockedByGroupPayment()) {
            return back()->with(
                'error',
                "This runner is part of group {$registration->group->reference_code}, whose payment has not been recorded yet."
            );
        }

        $this->approveOne($registration);

        // Approving the last outstanding member finishes the group, so the organizer
        // recap fires from here too, not only from the bulk action.
        $summarySent = $registration->group
            ? GroupSummaryNotifier::sendIfComplete($registration->group)
            : false;

        return back()->with(
            'success',
            "Registration approved. Bib #{$registration->bib_number} assigned."
            . ($summarySent ? " Group summary emailed to {$registration->group->organizer_email}." : '')
        );
    }

    private function approveOne(Registration $registration): void
    {
        $registration->status = 'approved';
        $registration->assignBibNumber();

        // Update payment proof status
        $registration->paymentProof?->update([
            'status'      => 'verified',
            'verified_at' => now(),
        ]);

        Mail::to($registration->email)->send(new RegistrationApproved($registration));
    }

    // Reject registration
    public function reject(Request $request, Registration $registration)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $registration->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        $registration->paymentProof?->update(['status' => 'rejected']);

        Mail::to($registration->email)->send(new RegistrationRejected($registration));

        return back()->with('success', 'Registration rejected.');
    }

    // Resend approval confirmation email for approved registrations
    public function resendEmail(Registration $registration)
    {
        abort_unless($registration->status === 'approved', 403, 'Only approved registrations can have the confirmation email resent.');

        Mail::to($registration->email)->send(new RegistrationApproved($registration));

        return back()->with('success', "Confirmation email resent to {$registration->email}.");
    }

    // Update bib number manually
    public function updateBib(Request $request, Registration $registration)
    {
        $request->validate([
            'bib_number' => 'required|integer|min:1',
        ]);

        $registration->update(['bib_number' => $request->bib_number]);

        return back()->with('success', 'Bib number updated.');
    }
}

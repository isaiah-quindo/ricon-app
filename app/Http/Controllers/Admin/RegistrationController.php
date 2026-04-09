<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use App\Models\Registration;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    // List all registrations
    public function index(Request $request)
    {
        $registrations = Registration::with(['raceCategory', 'paymentProof'])
            ->when($request->category, fn($q) => $q->where('race_category_id', $request->category))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('admin.registrations.index', compact('registrations'));
    }

    // View a single registration
    public function show(Registration $registration)
    {
        $registration->load(['raceCategory', 'paymentProof']);
        return view('admin.registrations.show', compact('registration'));
    }

    // Approve registration and assign bib number
    public function approve(Registration $registration)
    {
        $registration->status = 'approved';
        $registration->assignBibNumber();

        // Update payment proof status
        $registration->paymentProof?->update([
            'status'      => 'verified',
            'verified_at' => now(),
        ]);

        Mail::to($registration->email)->send(new RegistrationApproved($registration));

        return back()->with('success', "Registration approved. Bib #{$registration->bib_number} assigned.");
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

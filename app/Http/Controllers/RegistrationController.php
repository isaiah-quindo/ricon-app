<?php

namespace App\Http\Controllers;

use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    // Show the registration form
    public function create()
    {
        $categories = RaceCategory::where('is_active', true)->orderByRaw('CAST(distance_km AS INTEGER) DESC')->get();
        return view('registration.create', compact('categories'));
    }

    // Handle form submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'race_category_id'         => 'required|exists:race_categories,id',
            'first_name'               => 'required|string|max:255',
            'last_name'                => 'required|string|max:255',
            'sex'                      => 'required|in:male,female',
            'mobile_number'            => 'required|string|max:20',
            'email'                    => 'required|email|max:255',
            'birthdate'                => 'required|date|before:today',
            'address'                  => 'required|string|max:500',
            'emergency_contact_name'   => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:20',
            'nationality'              => 'required|string|max:100',
            'affiliation'              => 'nullable|string|max:255',
            'shirt_size'               => 'required|in:XS,S,M,L,XL,XXL',
            'proof_of_payment'         => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'waiver_agreed'            => 'accepted',
            'terms_agreed'             => 'accepted',
        ]);

        // Create registration
        $registration = Registration::create([
            ...$validated,
            'waiver_agreed' => true,
            'terms_agreed'  => true,
            'status'        => 'payment_submitted',
        ]);

        // Store proof of payment
        $path = $request->file('proof_of_payment')->store('payment_proofs', 's3');

        PaymentProof::create([
            'registration_id' => $registration->id,
            'image_path'      => $path,
            'payment_method'  => $request->input('payment_method'),
            'status'          => 'pending',
        ]);

        return redirect()->route('registration.success')
            ->with('success', 'Registration submitted successfully!');
    }

    // Show success page
    public function success()
    {
        return view('registration.success');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
            'shirt_size'               => 'required|in:XS,S,M,L,XL,2XL',
            'proof_of_payment'         => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'waiver_agreed'            => 'accepted',
            'terms_agreed'             => 'accepted',
            'discount_code'            => 'nullable|string|max:50',
        ]);

        $proofFile = $request->file('proof_of_payment');
        $submittedCode = $request->input('discount_code');

        $registration = DB::transaction(function () use ($validated, $submittedCode) {
            $category = RaceCategory::findOrFail($validated['race_category_id']);

            $discountCode = null;
            $discountAmount = 0.0;

            if (! empty($submittedCode)) {
                $discountCode = DiscountCode::whereRaw('UPPER(code) = ?', [strtoupper($submittedCode)])
                    ->lockForUpdate()
                    ->first();

                $error = $discountCode?->checkValidFor($category->id, $validated['email']);
                if (! $discountCode || $error) {
                    throw ValidationException::withMessages([
                        'discount_code' => $error ?? 'Discount code not found.',
                    ]);
                }

                $discountAmount = $discountCode->computeDiscount((float) $category->price);
                $discountCode->increment('used_count');
            }

            // Strip the discount_code field — it's not a column on registrations.
            $attrs = collect($validated)->except('discount_code')->all();

            return Registration::create([
                ...$attrs,
                'waiver_agreed'    => true,
                'terms_agreed'     => true,
                'status'           => 'payment_submitted',
                'price_paid'       => max(0, (float) $category->price - $discountAmount),
                'discount_code_id' => $discountCode?->id,
                'discount_amount'  => $discountCode ? $discountAmount : null,
            ]);
        });

        // Store proof of payment (outside the transaction — S3 upload is not transactional).
        $path = $proofFile->store('payment_proofs', 's3');

        PaymentProof::create([
            'registration_id' => $registration->id,
            'image_path'      => $path,
            'payment_method'  => $request->input('payment_method'),
            'status'          => 'pending',
        ]);

        return redirect()->route('registration.success')
            ->with('success', 'Registration submitted successfully!');
    }

    // AJAX endpoint used by the public registration form to validate a code before submit.
    public function validateDiscount(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|max:50',
            'race_category_id' => 'required|exists:race_categories,id',
            'email'            => 'nullable|email',
        ]);

        $code = DiscountCode::whereRaw('UPPER(code) = ?', [strtoupper($data['code'])])->first();
        if (! $code) {
            return response()->json(['valid' => false, 'message' => 'Code not found.'], 404);
        }

        if ($error = $code->checkValidFor($data['race_category_id'], $data['email'] ?? null)) {
            return response()->json(['valid' => false, 'message' => $error], 422);
        }

        $category = RaceCategory::findOrFail($data['race_category_id']);
        $discount = $code->computeDiscount((float) $category->price);

        return response()->json([
            'valid'           => true,
            'code'            => $code->code,
            'percentage'      => (float) $code->discount_percentage,
            'base_price'      => (float) $category->price,
            'discount_amount' => $discount,
            'total'           => max(0, (float) $category->price - $discount),
        ]);
    }

    // Show success page
    public function success()
    {
        return view('registration.success');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\RegistrationGroup;
use App\Models\PaymentProof;
use App\Services\GroupPricing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Two separate journeys share this controller:
 *
 *  - individual (`/register`)       one participant, one optional discount code
 *  - group      (`/register/group`) 5+ participants, automatic volume discount, no codes
 *
 * Keeping them apart is what lets the pricing stay simple: a discount code and a
 * group discount can never apply to the same submission, so there is nothing to
 * reconcile between them.
 */
class RegistrationController extends Controller
{
    // Hard ceiling on party size for a single submission.
    private const MAX_PARTICIPANTS = 20;

    // A group has to be at least this big; anything smaller registers individually.
    private const MIN_GROUP_PARTICIPANTS = 5;

    // ------------------------------------------------------------------
    // Individual
    // ------------------------------------------------------------------

    public function create()
    {
        return view('registration.create', [
            'categories' => $this->activeCategories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [...$this->participantRules(1, 1), 'discount_code' => 'nullable|string|max:50'],
            [],
            $this->attributeNames($request, prefixWithNumber: false),
        );

        $participants  = array_values($validated['participants']);
        $submittedCode = $request->input('discount_code');
        $paymentMethod = $validated['payment_method'] ?? null;

        // Upload before opening the transaction. A failed upload then leaves nothing
        // behind, whereas a failed transaction only orphans an unreferenced S3 object.
        $path = $request->file('proof_of_payment')->store('payment_proofs', 's3');

        DB::transaction(function () use ($participants, $submittedCode, $path, $paymentMethod) {
            $discountCode = $submittedCode ? $this->resolveCode($submittedCode, $participants[0]) : null;
            $pricing = GroupPricing::make($participants, $discountCode);

            // No RegistrationGroup here. Groups start at five people, so a party of one
            // is just a registration with its own payment proof.
            $this->createRegistrations($participants, $pricing->allocations(), null, $path, $paymentMethod);

            if ($pricing->discountSource() === 'code') {
                $discountCode->increment('used_count');
            }
        });

        return redirect()->route('registration.success')
            ->with('success', 'Registration submitted successfully!');
    }

    // ------------------------------------------------------------------
    // Group
    // ------------------------------------------------------------------

    public function createGroup()
    {
        return view('registration.group', [
            'categories'           => $this->activeCategories(),
            'initialParticipants'  => self::MIN_GROUP_PARTICIPANTS,
            'minParticipants'      => self::MIN_GROUP_PARTICIPANTS,
            'maxParticipants'      => self::MAX_PARTICIPANTS,
        ]);
    }

    public function storeGroup(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                ...$this->participantRules(self::MIN_GROUP_PARTICIPANTS, self::MAX_PARTICIPANTS),
                // Whoever booked the group. Not necessarily one of the runners.
                'organizer.name'   => 'required|string|max:255',
                'organizer.email'  => 'required|email|max:255',
                'organizer.mobile' => 'required|string|max:20',
                'organizer.team'   => 'nullable|string|max:255',
            ],
            ['participants.min' => 'Group registration is for :min participants or more. Please register individually instead.'],
            [
                ...$this->attributeNames($request),
                'organizer.name'   => 'organizer name',
                'organizer.email'  => 'organizer email address',
                'organizer.mobile' => 'organizer mobile number',
                'organizer.team'   => 'organizer team / club',
            ],
        );

        // Any discount_code in the payload is ignored rather than rejected, so a stale
        // or hand-crafted field can never influence the price on this page.
        return $this->persist(
            participants: array_values($validated['participants']),
            proof: $request->file('proof_of_payment'),
            paymentMethod: $validated['payment_method'] ?? null,
            organizer: $validated['organizer'],
        );
    }

    // ------------------------------------------------------------------
    // Persistence
    // ------------------------------------------------------------------

    /** Group registration: one RegistrationGroup recording the transaction, plus N runners. */
    private function persist(
        array $participants,
        UploadedFile $proof,
        ?string $paymentMethod,
        array $organizer,
    ): RedirectResponse {
        $path = $proof->store('payment_proofs', 's3');

        $group = DB::transaction(function () use ($participants, $path, $paymentMethod, $organizer) {
            $pricing = GroupPricing::make($participants);

            $group = RegistrationGroup::create([
                'reference_code'            => RegistrationGroup::generateReferenceCode(),
                'leader_email'              => $participants[0]['email'],
                'organizer_name'            => $organizer['name'],
                'organizer_email'           => $organizer['email'],
                'organizer_mobile'          => $organizer['mobile'],
                'organizer_team'            => $organizer['team'] ?? null,
                'participant_count'         => $pricing->count(),
                'subtotal'                  => $pricing->subtotal(),
                'group_discount_percentage' => $pricing->groupPercentage(),
                'discount_source'           => $pricing->discountSource(),
                'discount_total'            => $pricing->discountTotal(),
                'total_due'                 => $pricing->totalDue(),
                'payment_method'            => $paymentMethod,
                'payment_status'            => 'pending',
            ]);

            $this->createRegistrations($participants, $pricing->allocations(), $group, $path, $paymentMethod);

            return $group;
        });

        // The reference also rides in the URL so a page refresh does not lose it.
        return redirect()->route('registration.success', ['ref' => $group->reference_code])
            ->with('group_reference', $group->reference_code)
            ->with('participant_count', $group->participant_count)
            ->with('success', 'Registration submitted successfully!');
    }

    /**
     * Creates a Registration per participant, each with its own PaymentProof row.
     *
     * A group shares one uploaded receipt across those rows so the per-person admin
     * approve/reject flow keeps working unchanged; an individual simply has one.
     *
     * @param  array<int, array{price_paid: float, discount_amount: float|null, discount_code_id: string|null}>  $allocations
     */
    private function createRegistrations(
        array $participants,
        array $allocations,
        ?RegistrationGroup $group,
        string $path,
        ?string $paymentMethod,
    ): void {
        foreach ($participants as $i => $participant) {
            $registration = Registration::create([
                ...$participant,
                ...$allocations[$i],
                'registration_group_id' => $group?->id,
                'waiver_agreed'         => true,
                'terms_agreed'          => true,
                'status'                => 'payment_submitted',
            ]);

            PaymentProof::create([
                'registration_id' => $registration->id,
                'image_path'      => $path,
                'payment_method'  => $paymentMethod,
                'status'          => 'pending',
            ]);
        }
    }

    /** Locks and validates a submitted code against the single participant using it. */
    private function resolveCode(string $submittedCode, array $participant): DiscountCode
    {
        $code = DiscountCode::whereRaw('UPPER(code) = ?', [strtoupper($submittedCode)])
            ->lockForUpdate()
            ->first();

        if (! $code) {
            throw ValidationException::withMessages(['discount_code' => 'Discount code not found.']);
        }

        if ($error = $code->checkValidFor($participant['race_category_id'], $participant['email'] ?? null)) {
            throw ValidationException::withMessages(['discount_code' => $error]);
        }

        return $code;
    }

    // ------------------------------------------------------------------
    // Validation
    // ------------------------------------------------------------------

    private function participantRules(int $min, int $max): array
    {
        return [
            'participants'                            => "required|array|min:{$min}|max:{$max}",
            'participants.*.race_category_id'         => 'required|exists:race_categories,id',
            'participants.*.first_name'               => 'required|string|max:255',
            'participants.*.last_name'                => 'required|string|max:255',
            'participants.*.sex'                      => 'required|in:male,female',
            'participants.*.mobile_number'            => 'required|string|max:20',
            'participants.*.email'                    => 'required|email|max:255',
            'participants.*.birthdate'                => 'required|date|before:today',
            // The registrations.address column is a string(255), so 255 is the real ceiling.
            'participants.*.address'                  => 'required|string|max:255',
            'participants.*.nationality'              => 'required|string|max:100',
            'participants.*.affiliation'              => 'nullable|string|max:255',
            'participants.*.shirt_size'               => 'required|in:XS,S,M,L,XL,2XL',
            'participants.*.emergency_contact_name'   => 'required|string|max:255',
            'participants.*.emergency_contact_number' => 'required|string|max:20',
            'proof_of_payment'                        => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'payment_method'                          => 'nullable|string|max:50',
            'waiver_agreed'                           => 'accepted',
            'terms_agreed'                            => 'accepted',
        ];
    }

    /**
     * Turn "participants.1.first_name" into "participant 2 first name" so the error
     * summary at the top of the form tells you which person to go fix. The individual
     * page has only one participant, so it drops the numbering.
     */
    private function attributeNames(Request $request, bool $prefixWithNumber = true): array
    {
        $fields = [
            'race_category_id'         => 'race category',
            'first_name'               => 'first name',
            'last_name'                => 'last name',
            'sex'                      => 'sex',
            'mobile_number'            => 'mobile number',
            'email'                    => 'email address',
            'birthdate'                => 'birthdate',
            'address'                  => 'home address',
            'nationality'              => 'nationality',
            'affiliation'              => 'team / affiliation',
            'shirt_size'               => 'shirt size',
            'emergency_contact_name'   => 'emergency contact name',
            'emergency_contact_number' => 'emergency contact number',
        ];

        $names = [];
        $count = max(1, count((array) $request->input('participants', [])));

        for ($i = 0; $i < $count; $i++) {
            foreach ($fields as $field => $label) {
                $names["participants.$i.$field"] = $prefixWithNumber
                    ? 'participant ' . ($i + 1) . ' ' . $label
                    : $label;
            }
        }

        $names['proof_of_payment'] = 'proof of payment';
        $names['waiver_agreed']    = 'liability waiver';
        $names['terms_agreed']     = 'rules and conditions';

        return $names;
    }

    // ------------------------------------------------------------------
    // Shared
    // ------------------------------------------------------------------

    /** AJAX endpoint used by the individual form to price a code before submit. */
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
            'valid'      => true,
            'code'       => $code->code,
            'percentage' => (float) $code->discount_percentage,
            // Every category the code covers, so the form can keep the total correct
            // if the runner switches distance without re-checking the code.
            'race_category_ids' => $code->raceCategoryIds(),
            'base_price'        => (float) $category->price,
            'discount_amount'   => $discount,
            'total'             => max(0, (float) $category->price - $discount),
        ]);
    }

    public function success(Request $request)
    {
        // Prefer the flash, but fall back to ?ref= so a refresh still shows the
        // reference code. The lookup keeps an arbitrary ?ref= from being echoed back.
        $group = null;
        if ($reference = $request->query('ref')) {
            $group = RegistrationGroup::where('reference_code', $reference)->first();
        }

        return view('registration.success', [
            'groupReference'   => session('group_reference', $group?->reference_code),
            'participantCount' => (int) session('participant_count', $group?->participant_count ?? 1),
        ]);
    }

    private function activeCategories()
    {
        return RaceCategory::where('is_active', true)
            ->orderByRaw('CAST(distance_km AS INTEGER) DESC')
            ->get();
    }
}

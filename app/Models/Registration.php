<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'race_category_id',
        'first_name',
        'last_name',
        'sex',
        'mobile_number',
        'email',
        'birthdate',
        'address',
        'emergency_contact_name',
        'emergency_contact_number',
        'shirt_size',
        'nationality',
        'affiliation',
        'waiver_agreed',
        'terms_agreed',
        'bib_number',
        'status',
        'admin_notes',
        'price_paid',
        'discount_code_id',
        'discount_amount',
        'registration_group_id',
    ];

    protected $casts = [
        'birthdate'       => 'date',
        'waiver_agreed'   => 'boolean',
        'terms_agreed'    => 'boolean',
        'price_paid'      => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function raceCategory()
    {
        return $this->belongsTo(RaceCategory::class);
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }

    // Null for individual registrations, which are not part of any group.
    public function group()
    {
        return $this->belongsTo(RegistrationGroup::class, 'registration_group_id');
    }

    /**
     * A group is paid for by one transfer, so nobody in it can be approved until that
     * transfer has been recorded. Individual registrations are unaffected — their proof
     * is reviewed as part of approving them.
     */
    public function isBlockedByGroupPayment(): bool
    {
        return $this->group !== null && ! $this->group->isPaymentVerified();
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Auto-assign next bib number when approved (stored as integer)
    public function assignBibNumber(): void
    {
        $lastBib = Registration::where('race_category_id', $this->race_category_id)
            ->whereNotNull('bib_number')
            ->max('bib_number');

        $this->bib_number = $lastBib ? $lastBib + 1 : 1;

        $this->save();
    }

    // Display format: {bib_start_number}-{bib_number padded to 3 digits}
    // e.g. bib_start=100, bib_number=1 → "100-001"
    public function getFormattedBibAttribute(): ?string
    {
        if (! $this->bib_number || ! $this->raceCategory) {
            return null;
        }

        return $this->raceCategory->bib_start_number . '-' . str_pad($this->bib_number, 3, '0', STR_PAD_LEFT);
    }
}

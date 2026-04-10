<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Registration extends Model
{
    use HasUuids;

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
        'waiver_agreed',
        'terms_agreed',
        'bib_number',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'birthdate'     => 'date',
        'waiver_agreed' => 'boolean',
        'terms_agreed'  => 'boolean',
    ];

    public function raceCategory()
    {
        return $this->belongsTo(RaceCategory::class);
    }

    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
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

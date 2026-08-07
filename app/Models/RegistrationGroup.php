<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrationGroup extends Model
{
    use HasFactory, HasUuids;

    // Volume discount tiers, as [minimum participants => percentage off].
    // Thresholds are minimums: 7 participants gets 5%, 12 gets 10%.
    public const TIERS = [
        10 => 10.0,
        5  => 5.0,
    ];

    protected $fillable = [
        'reference_code',
        'leader_email',
        'organizer_name',
        'organizer_email',
        'organizer_mobile',
        'organizer_team',
        'participant_count',
        'subtotal',
        'group_discount_percentage',
        'discount_source',
        'discount_code_id',
        'discount_total',
        'total_due',
        'payment_method',
        'payment_status',
        'amount_received',
        'payment_reference',
        'verified_at',
        'verified_by',
        'admin_notes',
        'organizer_notified_at',
    ];

    protected $casts = [
        'participant_count'         => 'integer',
        'subtotal'                  => 'decimal:2',
        'group_discount_percentage' => 'decimal:2',
        'discount_total'            => 'decimal:2',
        'total_due'                 => 'decimal:2',
        'amount_received'           => 'decimal:2',
        'verified_at'               => 'datetime',
        'organizer_notified_at'     => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Real groups only.
     *
     * Individual registrations no longer create a group row at all, but rows created
     * before that change can be parties of one, and those are not group transactions.
     */
    public function scopeGroups($query)
    {
        return $query->where('participant_count', '>', 1);
    }

    /** The one uploaded receipt, shared by every member of the group. */
    public function paymentProof()
    {
        return $this->hasOneThrough(
            PaymentProof::class,
            Registration::class,
            'registration_group_id',
            'registration_id',
            'id',
            'id',
        );
    }

    // ------------------------------------------------------------------
    // Payment
    // ------------------------------------------------------------------

    public function isPaymentVerified(): bool
    {
        return $this->payment_status === 'verified';
    }

    /** Positive when they underpaid, negative when they sent too much. */
    public function paymentShortfall(): ?float
    {
        if ($this->amount_received === null) {
            return null;
        }

        return round((float) $this->total_due - (float) $this->amount_received, 2);
    }

    public function hasPaymentDiscrepancy(): bool
    {
        $shortfall = $this->paymentShortfall();

        return $shortfall !== null && abs($shortfall) >= 0.01;
    }

    /** How far through approval the party is, for the admin list. */
    public function approvedCount(): int
    {
        return $this->registrations->where('status', 'approved')->count();
    }

    /** Members still awaiting a decision. Rejected ones are already decided. */
    public function pendingCount(): int
    {
        return $this->registrations
            ->whereNotIn('status', ['approved', 'rejected'])
            ->count();
    }

    /**
     * Every member has a final outcome, so the organizer summary can be sent.
     * Rejected counts as resolved: the organizer still needs to know.
     */
    public function allMembersResolved(): bool
    {
        $members = $this->relationLoaded('registrations')
            ? $this->registrations
            : $this->registrations()->get();

        return $members->isNotEmpty()
            && $members->every(fn ($r) => in_array($r->status, ['approved', 'rejected'], true));
    }

    public function organizerNotified(): bool
    {
        return $this->organizer_notified_at !== null;
    }

    // Group discount percentage earned by a party of $count.
    public static function tierFor(int $count): float
    {
        foreach (self::TIERS as $minimum => $percentage) {
            if ($count >= $minimum) {
                return $percentage;
            }
        }

        return 0.0;
    }

    // Participants still needed to reach the next tier, or null once the top tier is reached.
    public static function nextTierFor(int $count): ?array
    {
        $tiers = array_reverse(self::TIERS, true);

        foreach ($tiers as $minimum => $percentage) {
            if ($count < $minimum) {
                return ['needed' => $minimum - $count, 'percentage' => $percentage];
            }
        }

        return null;
    }

    // Ambiguous characters (0/O, 1/I) are left out so codes survive being read over the phone.
    private const REFERENCE_ALPHABET = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    public static function generateReferenceCode(): string
    {
        do {
            $code = 'GRP-';
            for ($i = 0; $i < 6; $i++) {
                $code .= self::REFERENCE_ALPHABET[random_int(0, strlen(self::REFERENCE_ALPHABET) - 1)];
            }
        } while (static::where('reference_code', $code)->exists());

        return $code;
    }

    public function isGroup(): bool
    {
        return $this->participant_count > 1;
    }
}

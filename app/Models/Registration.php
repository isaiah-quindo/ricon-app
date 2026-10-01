<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () {
            $this->bib_number = self::nextBibNumberFor($this->race_category_id);
            $this->save();
        });
    }

    /**
     * Next free bib in a category: one above the highest taken, so it never collides.
     * Must run inside a transaction. Locking the category row makes concurrent bib
     * assignments for the same category wait their turn (Postgres disallows FOR UPDATE
     * on MAX(), so the lock goes on the parent row instead).
     */
    public static function nextBibNumberFor(string $raceCategoryId): int
    {
        RaceCategory::whereKey($raceCategoryId)->lockForUpdate()->first();

        $lastBib = Registration::where('race_category_id', $raceCategoryId)
            ->whereNotNull('bib_number')
            ->max('bib_number');

        return $lastBib ? $lastBib + 1 : 1;
    }

    /**
     * Admin notes split into display entries. Category changes become
     * ['type' => 'change', 'title', 'meta', 'rows' => [label => value]]; anything else is
     * ['type' => 'text', 'text']. Reads both the block format written by changeCategory()
     * and the earlier one-line "[date] Category changed …" format.
     */
    public function adminNoteEntries(): array
    {
        if (blank($this->admin_notes)) {
            return [];
        }

        $entries = [];

        foreach (preg_split('/\R\s*\R/', trim($this->admin_notes)) as $block) {
            $lines = preg_split('/\R/', trim($block));

            if (str_starts_with($lines[0], 'Category change · ')) {
                $meta = explode(' · ', $lines[0]);
                $rows = [];
                foreach (array_slice($lines, 1) as $line) {
                    [$label, $value] = array_pad(explode(': ', $line, 2), 2, '');
                    $rows[$label] = $value;
                }
                $entries[] = ['type' => 'change', 'title' => $meta[0], 'meta' => implode(' · ', array_slice($meta, 1)), 'rows' => $rows];
                continue;
            }

            // Older one-line entries may sit next to plain notes in the same block, so go line by line.
            $text = [];
            foreach ($lines as $line) {
                if ($legacy = self::parseLegacyChangeLine($line)) {
                    if ($text) {
                        $entries[] = ['type' => 'text', 'text' => implode("\n", $text)];
                        $text = [];
                    }
                    $entries[] = $legacy;
                } else {
                    $text[] = $line;
                }
            }
            if ($text) {
                $entries[] = ['type' => 'text', 'text' => implode("\n", $text)];
            }
        }

        return $entries;
    }

    private static function parseLegacyChangeLine(string $line): ?array
    {
        $pattern = '/^\[(?<date>[^\]]+)\] Category changed (?<from>.+?) → (?<to>.+?)\.'
            . '(?: Bib (?<bibFrom>\S+) → (?<bibTo>\S+)\.)?'
            . ' Paid (?<paid>.+?) by (?<by>.+?)\.(?: Note: (?<note>.*))?$/u';

        if (! preg_match($pattern, trim($line), $m)) {
            return null;
        }

        return [
            'type'  => 'change',
            'title' => 'Category change',
            'meta'  => $m['date'] . ' · ' . $m['by'],
            'rows'  => array_filter([
                'Category' => "{$m['from']} → {$m['to']}",
                'Bib'      => ($m['bibFrom'] ?? '') !== '' ? "{$m['bibFrom']} → {$m['bibTo']}" : null,
                'Paid'     => $m['paid'],
                'Note'     => ($m['note'] ?? '') !== '' ? $m['note'] : null,
            ]),
        ];
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

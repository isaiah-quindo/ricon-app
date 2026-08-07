<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\RaceCategory;
use App\Models\RegistrationGroup;
use Illuminate\Support\Collection;

/**
 * Works out what a party of participants owes.
 *
 * The group discount and a discount code never stack — whichever is worth more
 * to the customer wins, and ties go to the group discount.
 *
 * A solo registration is just a party of one, so this handles that case too:
 * it earns no group discount and a code applies exactly as it always has.
 */
class GroupPricing
{
    /**
     * @param  Collection<int, RaceCategory>  $categories  keyed by category id
     * @param  array<int, array{race_category_id: string}>  $participants
     */
    public function __construct(
        private Collection $categories,
        private array $participants,
        private ?DiscountCode $code = null,
    ) {}

    public static function make(array $participants, ?DiscountCode $code = null): self
    {
        $categories = RaceCategory::whereIn(
            'id',
            collect($participants)->pluck('race_category_id')->unique()->all()
        )->get()->keyBy('id');

        return new self($categories, $participants, $code);
    }

    public function count(): int
    {
        return count($this->participants);
    }

    public function priceFor(int $index): float
    {
        $categoryId = $this->participants[$index]['race_category_id'] ?? null;

        return (float) ($this->categories[$categoryId]->price ?? 0);
    }

    public function subtotal(): float
    {
        return round(
            collect($this->participants)
                ->keys()
                ->sum(fn ($i) => $this->priceFor($i)),
            2
        );
    }

    public function groupPercentage(): float
    {
        return RegistrationGroup::tierFor($this->count());
    }

    public function groupDiscount(): float
    {
        return round($this->subtotal() * $this->groupPercentage() / 100, 2);
    }

    /**
     * A DiscountCode covers one or more race categories, so it only discounts a
     * participant racing one of them — a 100K-only code must not touch a 10K entry.
     * Only individual registration supplies a code, so this sees one participant.
     */
    public function codeDiscount(): float
    {
        if (! $this->code) {
            return 0.0;
        }

        return round(
            collect($this->participants)
                ->keys()
                ->filter(fn ($i) => $this->code->appliesTo($this->participants[$i]['race_category_id']))
                ->sum(fn ($i) => $this->code->computeDiscount($this->priceFor($i))),
            2
        );
    }

    /**
     * none | group | code
     *
     * The two are mutually exclusive by construction: individual registration is a
     * party of one (so the tier is always 0) and group registration never accepts a
     * code. No comparison between them is needed.
     */
    public function discountSource(): string
    {
        if ($this->code && $this->codeDiscount() > 0) {
            return 'code';
        }

        return $this->groupDiscount() > 0 ? 'group' : 'none';
    }

    public function discountTotal(): float
    {
        return match ($this->discountSource()) {
            'group' => $this->groupDiscount(),
            'code'  => $this->codeDiscount(),
            default => 0.0,
        };
    }

    public function totalDue(): float
    {
        return round(max(0, $this->subtotal() - $this->discountTotal()), 2);
    }

    /**
     * Per-participant money, ready to merge into a Registration.
     *
     * The discount is computed per person rather than divided out of a group total,
     * so the rows always add back up to totalDue() with no rounding remainder.
     *
     * @return array<int, array{price_paid: float, discount_amount: float|null, discount_code_id: string|null}>
     */
    public function allocations(): array
    {
        $source = $this->discountSource();

        return collect($this->participants)->keys()->map(function ($i) use ($source) {
            $price = $this->priceFor($i);

            $discount = match (true) {
                $source === 'group' => round($price * $this->groupPercentage() / 100, 2),
                $source === 'code' && $this->code->appliesTo($this->participants[$i]['race_category_id'])
                    => $this->code->computeDiscount($price),
                default => 0.0,
            };

            return [
                'price_paid'       => round(max(0, $price - $discount), 2),
                'discount_amount'  => $discount > 0 ? $discount : null,
                'discount_code_id' => $source === 'code' && $discount > 0 ? $this->code->id : null,
            ];
        })->all();
    }

    /** Payload shared by the AJAX quote endpoint and the review panel. */
    public function toArray(): array
    {
        $next = RegistrationGroup::nextTierFor($this->count());

        return [
            'participant_count' => $this->count(),
            'subtotal'          => $this->subtotal(),
            'group_percentage'  => $this->groupPercentage(),
            'group_discount'    => $this->groupDiscount(),
            'code_discount'     => $this->codeDiscount(),
            'discount_source'   => $this->discountSource(),
            'discount_total'    => $this->discountTotal(),
            'total'             => $this->totalDue(),
            'next_tier'         => $next,
        ];
    }
}

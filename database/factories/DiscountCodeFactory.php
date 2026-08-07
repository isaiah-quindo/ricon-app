<?php

namespace Database\Factories;

use App\Models\DiscountCode;
use App\Models\RaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DiscountCode>
 */
class DiscountCodeFactory extends Factory
{
    protected $model = DiscountCode::class;

    public function definition(): array
    {
        return [
            'code'                => Str::upper(Str::random(8)),
            'discount_percentage' => 10,
            'max_uses'            => null,
            'used_count'          => 0,
            'expires_at'          => null,
            'one_per_email'       => false,
            'is_active'           => true,
            'description'         => null,
        ];
    }

    /**
     * Attach the categories the code covers.
     *
     * Accepts models or ids. Without this a code covers nothing and will never apply,
     * so tests should always pass at least one.
     */
    public function forCategories(...$categories): static
    {
        $ids = collect($categories)
            ->flatten()
            ->map(fn ($c) => $c instanceof RaceCategory ? $c->id : $c)
            ->all();

        return $this->afterCreating(fn (DiscountCode $code) => $code->raceCategories()->sync($ids));
    }
}

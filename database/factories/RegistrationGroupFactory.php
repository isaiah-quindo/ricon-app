<?php

namespace Database\Factories;

use App\Models\RegistrationGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RegistrationGroup>
 */
class RegistrationGroupFactory extends Factory
{
    protected $model = RegistrationGroup::class;

    public function definition(): array
    {
        $count    = 5;
        $subtotal = $count * 3000;
        $discount = round($subtotal * 0.05, 2);

        return [
            'reference_code'            => 'GRP-' . Str::upper(Str::random(6)),
            'leader_email'              => fake()->unique()->safeEmail(),
            'organizer_name'            => fake()->name(),
            'organizer_email'           => fake()->unique()->safeEmail(),
            'organizer_mobile'          => '+63 917 000 0000',
            'organizer_team'            => 'Test Running Club',
            'participant_count'         => $count,
            'subtotal'                  => $subtotal,
            'group_discount_percentage' => 5,
            'discount_source'           => 'group',
            'discount_total'            => $discount,
            'total_due'                 => $subtotal - $discount,
            'payment_method'            => 'Bank Transfer',
            'payment_status'            => 'pending',
        ];
    }

    /** A legacy party of one, which predates groups being 5+ only. */
    public function partyOfOne(): static
    {
        return $this->state(fn () => [
            'participant_count'         => 1,
            'subtotal'                  => 3000,
            'group_discount_percentage' => 0,
            'discount_source'           => 'none',
            'discount_total'            => 0,
            'total_due'                 => 3000,
        ]);
    }
}

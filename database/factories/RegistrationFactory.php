<?php

namespace Database\Factories;

use App\Models\RaceCategory;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'race_category_id'         => RaceCategory::factory(),
            'first_name'               => fake()->firstName(),
            'last_name'                => fake()->lastName(),
            'sex'                      => fake()->randomElement(['male', 'female']),
            'mobile_number'            => '+63 917 000 0000',
            'email'                    => fake()->unique()->safeEmail(),
            'birthdate'                => fake()->dateTimeBetween('-50 years', '-19 years')->format('Y-m-d'),
            'address'                  => fake()->address(),
            'emergency_contact_name'   => fake()->name(),
            'emergency_contact_number' => '+63 918 000 0000',
            'shirt_size'               => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', '2XL']),
            'nationality'              => 'Filipino',
            'affiliation'              => null,
            'waiver_agreed'            => true,
            'terms_agreed'             => true,
            'status'                   => 'payment_submitted',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => ['status' => 'approved']);
    }
}

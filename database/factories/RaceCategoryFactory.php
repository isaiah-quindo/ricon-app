<?php

namespace Database\Factories;

use App\Models\RaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RaceCategory>
 */
class RaceCategoryFactory extends Factory
{
    protected $model = RaceCategory::class;

    public function definition(): array
    {
        $distance = fake()->randomElement([10, 21, 60, 100]);

        return [
            'name'             => $distance . ' KM',
            'slug'             => $distance . 'km-' . Str::lower(Str::random(6)),
            'price'            => $distance * 100,
            'distance_km'      => (string) $distance,
            'elevation_m'      => (string) ($distance * 30),
            'description'      => fake()->sentence(),
            'max_slots'        => 200,
            'bib_start_number' => $distance * 10,
            'is_active'        => true,
        ];
    }

    public function priced(float $price): static
    {
        return $this->state(fn () => ['price' => $price]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}

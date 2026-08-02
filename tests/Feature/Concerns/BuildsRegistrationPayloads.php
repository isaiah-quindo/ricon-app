<?php

namespace Tests\Feature\Concerns;

use App\Models\RaceCategory;
use Illuminate\Http\UploadedFile;

/** Shared payload builders for the individual and group registration tests. */
trait BuildsRegistrationPayloads
{
    protected function participant(RaceCategory $category, int $n = 1): array
    {
        return [
            'race_category_id'         => $category->id,
            'first_name'               => 'Runner' . $n,
            'last_name'                => 'Test',
            'sex'                      => 'male',
            'mobile_number'            => '+63 917 000 0000',
            'email'                    => "runner{$n}@example.com",
            'birthdate'                => '1990-05-05',
            'address'                  => '123 Session Road, Baguio City',
            'nationality'              => 'Filipino',
            'affiliation'              => null,
            'shirt_size'               => 'M',
            'emergency_contact_name'   => 'Contact ' . $n,
            'emergency_contact_number' => '+63 918 000 0000',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    protected function participants(RaceCategory $category, int $count): array
    {
        return collect(range(1, $count))
            ->map(fn ($n) => $this->participant($category, $n))
            ->all();
    }

    /**
     * A submission payload. The organizer block is only read by group registration;
     * the individual route ignores it, so it is harmless to include by default.
     */
    protected function payload(array $participants, array $overrides = []): array
    {
        return array_merge([
            'participants'     => $participants,
            'organizer'        => $this->organizerPayload(),
            'payment_method'   => 'Bank Transfer',
            'proof_of_payment' => UploadedFile::fake()->image('proof.jpg'),
            'waiver_agreed'    => '1',
            'terms_agreed'     => '1',
        ], $overrides);
    }

    protected function organizerPayload(array $overrides = []): array
    {
        return array_merge([
            'name'   => 'Coach Reyes',
            'email'  => 'coach@club.example',
            'mobile' => '+63 917 111 2222',
            'team'   => 'Baguio Trail Club',
        ], $overrides);
    }
}

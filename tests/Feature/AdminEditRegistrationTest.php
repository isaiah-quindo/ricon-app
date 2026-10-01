<?php

namespace Tests\Feature;

use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Admins can correct a participant's details, but never their category, status, bib or pricing. */
class AdminEditRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name'               => 'Juan',
            'last_name'                => 'Dela Cruz',
            'sex'                      => 'male',
            'email'                    => 'juan@example.com',
            'mobile_number'            => '+63 917 123 4567',
            'birthdate'                => '1990-05-15',
            'address'                  => 'Session Road, Baguio City',
            'nationality'              => 'Filipino',
            'affiliation'              => 'Baguio Trail Club',
            'shirt_size'               => 'XL',
            'emergency_contact_name'   => 'Maria Dela Cruz',
            'emergency_contact_number' => '+63 918 765 4321',
        ], $overrides);
    }

    public function test_admin_can_open_the_edit_form(): void
    {
        $registration = Registration::factory()->create();

        $this->actingAs($this->admin())
            ->get(route('admin.registrations.edit', $registration))
            ->assertOk()
            ->assertSee($registration->first_name);
    }

    public function test_admin_can_update_participant_details(): void
    {
        $registration = Registration::factory()->approved()->create([
            'bib_number' => 7,
            'price_paid' => 3000,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.registrations.update', $registration), $this->validPayload())
            ->assertRedirect(route('admin.registrations.show', $registration))
            ->assertSessionHas('success');

        $registration->refresh();

        $this->assertSame('Juan', $registration->first_name);
        $this->assertSame('Dela Cruz', $registration->last_name);
        $this->assertSame('juan@example.com', $registration->email);
        $this->assertSame('XL', $registration->shirt_size);
        $this->assertSame('1990-05-15', $registration->birthdate->format('Y-m-d'));
        $this->assertSame('Maria Dela Cruz', $registration->emergency_contact_name);

        // Untouched by this form
        $this->assertSame('approved', $registration->status);
        $this->assertSame(7, (int) $registration->bib_number);
        $this->assertEquals(3000, $registration->price_paid);
    }

    public function test_category_status_and_bib_cannot_be_changed_through_the_form(): void
    {
        $registration = Registration::factory()->create(['bib_number' => null]);
        $originalCategory = $registration->race_category_id;
        $otherCategory = RaceCategory::factory()->create();

        $this->actingAs($this->admin())
            ->put(route('admin.registrations.update', $registration), $this->validPayload([
                'race_category_id' => $otherCategory->id,
                'status'           => 'approved',
                'bib_number'       => 99,
                'price_paid'       => 1,
            ]))
            ->assertRedirect();

        $registration->refresh();

        $this->assertSame($originalCategory, $registration->race_category_id);
        $this->assertSame('payment_submitted', $registration->status);
        $this->assertNull($registration->bib_number);
        $this->assertNull($registration->price_paid);
    }

    public function test_invalid_details_are_rejected(): void
    {
        $registration = Registration::factory()->create();

        $this->actingAs($this->admin())
            ->put(route('admin.registrations.update', $registration), $this->validPayload([
                'email'      => 'not-an-email',
                'shirt_size' => 'XXXL',
                'first_name' => '',
            ]))
            ->assertSessionHasErrors(['email', 'shirt_size', 'first_name']);

        $this->assertNotSame('not-an-email', $registration->fresh()->email);
    }

    public function test_non_admins_cannot_edit_registrations(): void
    {
        $registration = Registration::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.registrations.edit', $registration))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('admin.registrations.update', $registration), $this->validPayload())
            ->assertForbidden();

        $this->assertNotSame('juan@example.com', $registration->fresh()->email);
    }
}

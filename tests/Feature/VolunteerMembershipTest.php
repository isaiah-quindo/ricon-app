<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VolunteerMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolunteerMembershipTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name'        => 'Juan Dela Cruz',
            'email'            => 'volunteer@example.com',
            'mobile_number'    => '09171234567',
            'orientation_date' => '2026-10-10',
            'gcash_reference'  => '1234 5678 9012',
            'consent'          => true,
        ], $overrides);
    }

    public function test_membership_page_shows_steps_and_qr(): void
    {
        $this->get('/programs/volunteer/membership')
            ->assertOk()
            ->assertSee('Choose Your Orientation Date')
            ->assertSee('October 17, 2026')
            ->assertSee('images/volunteer/membership-gcash-qr.jpg')
            ->assertSee('Confirm Membership');
    }

    public function test_membership_confirmation_is_saved(): void
    {
        $this->postJson('/programs/volunteer/membership', $this->validPayload())
            ->assertOk()
            ->assertJson(['status' => 'created']);

        $membership = VolunteerMembership::sole();
        $this->assertSame('Juan Dela Cruz', $membership->full_name);
        $this->assertSame('2026-10-10', $membership->orientation_date->toDateString());
        $this->assertSame('1234 5678 9012', $membership->gcash_reference);
    }

    public function test_membership_requires_consent_valid_date_and_reference(): void
    {
        $this->postJson('/programs/volunteer/membership', $this->validPayload([
            'consent'          => false,
            'orientation_date' => '2026-10-11',
            'gcash_reference'  => '',
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['consent', 'orientation_date', 'gcash_reference']);

        $this->assertSame(0, VolunteerMembership::count());
    }

    public function test_admin_sees_filters_and_exports_memberships(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->postJson('/programs/volunteer/membership', $this->validPayload());
        $this->postJson('/programs/volunteer/membership', $this->validPayload([
            'full_name'        => 'Maria Santos',
            'orientation_date' => '2026-10-17',
            'gcash_reference'  => '9999 8888 7777',
        ]));

        $this->actingAs($admin)
            ->get('/admin/volunteer-memberships')
            ->assertOk()
            ->assertSee('Juan Dela Cruz')
            ->assertSee('Maria Santos');

        $this->actingAs($admin)
            ->get('/admin/volunteer-memberships?orientation_date=2026-10-17')
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertDontSee('Juan Dela Cruz');

        $response = $this->actingAs($admin)->get('/admin/volunteer-memberships/export');
        $response->assertOk();
        $this->assertStringContainsString('9999 8888 7777', $response->streamedContent());
    }

    public function test_non_admins_cannot_view_memberships(): void
    {
        $this->get('/admin/volunteer-memberships')->assertRedirect('/login');
    }
}

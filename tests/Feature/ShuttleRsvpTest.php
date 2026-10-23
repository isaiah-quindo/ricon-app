<?php

namespace Tests\Feature;

use App\Models\ShuttleRsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShuttleRsvpTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name'     => 'Juan Dela Cruz',
            'email'         => 'runner@example.com',
            'mobile_number' => '09171234567',
            'distance'      => '100KM',
            'seats'         => 2,
            'shuttle_date'  => '2026-11-13',
            'return_trip'   => true,
            'pickup_point'  => 'Cubao',
            'notes'         => 'Traveling with a teammate.',
        ], $overrides);
    }

    public function test_public_shuttle_page_is_removed(): void
    {
        $this->get('/shuttle')->assertNotFound();
        $this->postJson('/shuttle', $this->validPayload())->assertNotFound();

        $this->assertSame(0, ShuttleRsvp::count());
    }

    public function test_admin_sees_rsvps(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ShuttleRsvp::create($this->validPayload());
        ShuttleRsvp::create($this->validPayload(['full_name' => 'Maria Santos', 'email' => 'maria@example.com', 'seats' => 3, 'return_trip' => false]));

        $this->actingAs($admin)
            ->get('/admin/shuttle-rsvp')
            ->assertOk()
            ->assertSee('Juan Dela Cruz')
            ->assertSee('Maria Santos');
    }

    public function test_admin_can_filter_by_pickup_point(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ShuttleRsvp::create($this->validPayload());
        ShuttleRsvp::create($this->validPayload(['full_name' => 'Maria Santos', 'pickup_point' => 'NAIA']));

        $this->actingAs($admin)
            ->get('/admin/shuttle-rsvp?pickup_point=NAIA')
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertDontSee('Juan Dela Cruz');
    }

    public function test_admin_export_downloads_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ShuttleRsvp::create($this->validPayload());

        $response = $this->actingAs($admin)->get('/admin/shuttle-rsvp/export');

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('runner@example.com', $response->streamedContent());
    }

    public function test_non_admins_cannot_view_rsvps(): void
    {
        $this->get('/admin/shuttle-rsvp')->assertRedirect('/login');
    }
}

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

    public function test_shuttle_page_renders_the_form(): void
    {
        $this->get('/shuttle')
            ->assertOk()
            ->assertSee('Manila &rarr; Baguio shuttle', false)
            ->assertSee('Friday, November 13, 2026');
    }

    public function test_submission_creates_an_rsvp(): void
    {
        $this->postJson('/shuttle', $this->validPayload())
            ->assertOk()
            ->assertJson(['status' => 'created']);

        $rsvp = ShuttleRsvp::sole();
        $this->assertSame('runner@example.com', $rsvp->email);
        $this->assertSame(2, $rsvp->seats);
        $this->assertSame('2026-11-13', $rsvp->shuttle_date->toDateString());
        $this->assertTrue($rsvp->return_trip);
        $this->assertSame('Cubao', $rsvp->pickup_point);
    }

    public function test_submission_rejects_unknown_options_and_seat_limits(): void
    {
        $this->postJson('/shuttle', $this->validPayload([
            'pickup_point' => 'Makati',
            'shuttle_date' => '2026-11-15',
            'distance'     => '50KM',
            'seats'        => ShuttleRsvp::MAX_SEATS + 1,
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['pickup_point', 'shuttle_date', 'distance', 'seats']);

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

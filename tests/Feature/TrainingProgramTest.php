<?php

namespace Tests\Feature;

use App\Mail\TrainingProgramLink;
use App\Models\TrainingSignup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TrainingProgramTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name'     => 'Isaiah',
            'email'          => 'runner@example.com',
            'plan'           => 'tgc100k',
            'registered_tgc' => true,
        ], $overrides);
    }

    public function test_landing_page_renders_the_gate(): void
    {
        $this->get('/training')
            ->assertOk()
            ->assertSee('Start Training Free')
            ->assertSee('Your distance');
    }

    public function test_signup_creates_record_and_sends_link(): void
    {
        $response = $this->postJson('/training/signup', $this->validPayload([
            'email' => 'Runner@Example.com',
        ]));

        $response->assertOk()->assertJsonPath('status', 'created');

        $signup = TrainingSignup::first();
        $this->assertNotNull($signup);
        $this->assertSame('runner@example.com', $signup->email);
        $this->assertSame(64, strlen($signup->token));
        $this->assertTrue($signup->started_on->isToday());
        $this->assertSame($signup->program_url, $response->json('url'));

        Mail::assertSent(TrainingProgramLink::class, fn($mail) => $mail->hasTo('runner@example.com'));
    }

    public function test_duplicate_signup_resends_link_without_resetting_start_date(): void
    {
        $existing = TrainingSignup::create($this->validPayload([
            'started_on' => today()->subWeeks(3)->toDateString(),
        ]));

        $response = $this->postJson('/training/signup', $this->validPayload([
            'plan' => 'tgc60k', // different distance must not change the stored plan
        ]));

        $response->assertOk()
            ->assertJsonPath('status', 'existing')
            ->assertJsonMissingPath('url');

        $this->assertSame(1, TrainingSignup::count());

        $existing->refresh();
        $this->assertTrue($existing->started_on->equalTo(today()->subWeeks(3)));
        $this->assertSame('tgc100k', $existing->plan);

        Mail::assertSent(TrainingProgramLink::class, 1);
    }

    public function test_signup_validation_errors(): void
    {
        $this->postJson('/training/signup', $this->validPayload(['first_name' => '']))->assertStatus(422);
        $this->postJson('/training/signup', $this->validPayload(['email' => 'not-an-email']))->assertStatus(422);
        $this->postJson('/training/signup', $this->validPayload(['plan' => 'tgc21k']))->assertStatus(422);
    }

    public function test_program_page_renders_with_valid_token(): void
    {
        $signup = TrainingSignup::create($this->validPayload());

        $this->get("/training/p/{$signup->token}")
            ->assertOk()
            ->assertSee('program-data', false)
            ->assertSee($signup->first_name);
    }

    public function test_bogus_token_redirects_to_landing_as_expired(): void
    {
        $this->get('/training/p/bogus-token')
            ->assertRedirect(route('training.landing', ['expired' => 1]));
    }

    public function test_resend_is_enumeration_safe(): void
    {
        TrainingSignup::create($this->validPayload());

        $this->postJson('/training/resend', ['email' => 'runner@example.com'])
            ->assertOk()->assertJsonPath('status', 'ok');
        $this->postJson('/training/resend', ['email' => 'nobody@example.com'])
            ->assertOk()->assertJsonPath('status', 'ok');

        Mail::assertSent(TrainingProgramLink::class, 1);
    }

    public function test_signup_is_throttled(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/training/signup', $this->validPayload([
                'email' => "runner{$i}@example.com",
            ]))->assertOk();
        }

        $this->postJson('/training/signup', $this->validPayload([
            'email' => 'runner11@example.com',
        ]))->assertStatus(429);
    }

    public function test_mailchimp_subscribe_fires_and_marks_synced(): void
    {
        config(['services.mailchimp' => ['key' => 'test-key', 'list_id' => 'abc123', 'dc' => 'us21']]);
        Http::fake(['*.api.mailchimp.com/*' => Http::response(['status' => 'subscribed'])]);

        $this->postJson('/training/signup', $this->validPayload())->assertOk();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'us21.api.mailchimp.com/3.0/lists/abc123/members/')
                && ! str_ends_with($request->url(), '/tags')
                && $request['email_address'] === 'runner@example.com'
                && $request['status'] === 'subscribed';
        });

        $this->assertNotNull(TrainingSignup::first()->mailchimp_synced_at);
    }

    public function test_mailchimp_failure_does_not_block_signup(): void
    {
        config(['services.mailchimp' => ['key' => 'test-key', 'list_id' => 'abc123', 'dc' => 'us21']]);
        Http::fake(['*.api.mailchimp.com/*' => Http::response('server error', 500)]);

        $this->postJson('/training/signup', $this->validPayload())
            ->assertOk()->assertJsonPath('status', 'created');

        $this->assertNull(TrainingSignup::first()->mailchimp_synced_at);
    }

    public function test_mailchimp_skipped_when_not_configured(): void
    {
        config(['services.mailchimp' => ['key' => null, 'list_id' => null, 'dc' => null]]);
        Http::fake();

        $this->postJson('/training/signup', $this->validPayload())->assertOk();

        Http::assertNothingSent();
        $this->assertNull(TrainingSignup::first()->mailchimp_synced_at);
    }

    public function test_admin_pages_require_admin(): void
    {
        $this->get('/admin/training-signups')->assertRedirect('/login');

        $user = User::factory()->create(); // non-admin
        $this->actingAs($user)->get('/admin/training-signups')->assertForbidden();
    }

    public function test_admin_index_and_export(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $signup = TrainingSignup::create($this->validPayload());

        $this->actingAs($admin)->get('/admin/training-signups')
            ->assertOk()
            ->assertSee('runner@example.com');

        $response = $this->actingAs($admin)->get('/admin/training-signups/export');
        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $csv = $response->streamedContent();
        $this->assertStringContainsString('runner@example.com', $csv);
        $this->assertStringContainsString($signup->token, $csv);
    }

    public function test_admin_resend_link(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $signup = TrainingSignup::create($this->validPayload());

        $this->actingAs($admin)
            ->post("/admin/training-signups/{$signup->id}/resend-link")
            ->assertRedirect();

        $this->assertNotNull($signup->fresh()->link_last_sent_at);
        Mail::assertSent(TrainingProgramLink::class, 1);
    }

    public function test_program_week_follows_the_shared_calendar(): void
    {
        // Week 1 starts on the fixed program start date
        $this->travelTo('2026-06-01');
        $this->assertSame(1, TrainingSignup::currentProgramWeek());

        // Today (2026-07-05) falls in week 5 of the June 1 calendar
        $this->travelTo('2026-07-05');
        $this->assertSame(5, TrainingSignup::currentProgramWeek());

        // Clamped at 24 after the program ends
        $this->travelTo('2027-01-15');
        $this->assertSame(24, TrainingSignup::currentProgramWeek());

        $this->travelBack();
    }

    public function test_landing_shows_the_current_program_week(): void
    {
        $this->travelTo('2026-07-05');

        $this->get('/training')
            ->assertOk()
            ->assertSee('Week 5 of 24')
            ->assertSee('coachdon@edifyendurance.com');

        $this->travelBack();
    }
}

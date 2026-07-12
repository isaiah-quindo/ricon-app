<?php

namespace Tests\Feature;

use App\Models\QuizLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizLeadTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Isaiah',
            'email'      => 'runner@example.com',
            'score'      => 14,
        ], $overrides);
    }

    public function test_21km_page_renders_the_quiz(): void
    {
        $this->get('/race-category/21km')
            ->assertOk()
            ->assertSee('Readiness Check')
            ->assertSee('Are you ready for TGC 21K?');
    }

    public function test_quiz_submission_creates_a_lead_with_source_and_result(): void
    {
        $this->postJson('/race-category/21km/quiz', $this->validPayload())
            ->assertOk()
            ->assertJson(['status' => 'ok', 'result' => 'a']);

        $this->assertDatabaseHas('quiz_leads', [
            'email'  => 'runner@example.com',
            'source' => QuizLead::SOURCE_21K_QUIZ,
            'score'  => 14,
            'result' => 'a',
        ]);
    }

    public function test_result_thresholds(): void
    {
        $this->postJson('/race-category/21km/quiz', $this->validPayload(['email' => 'b@example.com', 'score' => 8]))
            ->assertJson(['result' => 'b']);

        $this->postJson('/race-category/21km/quiz', $this->validPayload(['email' => 'c@example.com', 'score' => 7]))
            ->assertJson(['result' => 'c']);
    }

    public function test_retaking_the_quiz_updates_the_existing_lead_instead_of_duplicating(): void
    {
        $this->postJson('/race-category/21km/quiz', $this->validPayload(['score' => 6]));
        $this->postJson('/race-category/21km/quiz', $this->validPayload(['email' => 'RUNNER@example.com', 'score' => 13]));

        $this->assertSame(1, QuizLead::count());
        $this->assertDatabaseHas('quiz_leads', [
            'email'  => 'runner@example.com',
            'score'  => 13,
            'result' => 'a',
        ]);
    }

    public function test_quiz_submission_validates_input(): void
    {
        $this->postJson('/race-category/21km/quiz', ['first_name' => '', 'email' => 'nope', 'score' => 99])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'email', 'score']);
    }

    public function test_admin_quiz_tab_lists_leads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        QuizLead::create([
            'first_name' => 'Maria',
            'email'      => 'maria@example.com',
            'source'     => QuizLead::SOURCE_21K_QUIZ,
            'score'      => 13,
            'result'     => 'a',
        ]);

        $this->actingAs($admin)
            ->get('/admin/training-signups?tab=quiz')
            ->assertOk()
            ->assertSee('maria@example.com')
            ->assertSee('Ready for 21K');
    }

    public function test_admin_quiz_export_downloads_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        QuizLead::create([
            'first_name' => 'Maria',
            'email'      => 'maria@example.com',
            'source'     => QuizLead::SOURCE_21K_QUIZ,
            'score'      => 9,
            'result'     => 'b',
        ]);

        $response = $this->actingAs($admin)->get('/admin/training-signups/export?tab=quiz');

        $response->assertOk();
        $this->assertStringContainsString('maria@example.com', $response->streamedContent());
        $this->assertStringContainsString('Almost there', $response->streamedContent());
    }
}

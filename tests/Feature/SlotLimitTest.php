<?php

namespace Tests\Feature;

use App\Models\RaceCategory;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\BuildsRegistrationPayloads;
use Tests\TestCase;

class SlotLimitTest extends TestCase
{
    use RefreshDatabase;
    use BuildsRegistrationPayloads;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        Mail::fake();
    }

    private function fill(RaceCategory $category, int $count, string $status = 'payment_submitted'): void
    {
        foreach ($this->participants($category, $count) as $participant) {
            Registration::create([...$participant, 'status' => $status, 'waiver_agreed' => true, 'terms_agreed' => true]);
        }
    }

    public function test_an_individual_cannot_register_for_a_full_category(): void
    {
        $category = RaceCategory::factory()->create(['max_slots' => 2]);
        $this->fill($category, 2);

        $this->post(route('registration.store'), $this->payload($this->participants($category, 1)))
            ->assertSessionHasErrors(['participants.0.race_category_id' => "{$category->name} slots are full. Please choose another category."]);

        $this->assertSame(2, Registration::count());
        $this->assertCount(0, Storage::disk('s3')->allFiles('payment_proofs'));
    }

    public function test_the_last_slot_can_still_be_taken(): void
    {
        $category = RaceCategory::factory()->create(['max_slots' => 2]);
        $this->fill($category, 1);

        $this->post(route('registration.store'), $this->payload($this->participants($category, 1)))
            ->assertRedirectContains(route('registration.success'));

        $this->assertSame(2, Registration::count());
    }

    public function test_rejected_registrations_free_their_slot(): void
    {
        $category = RaceCategory::factory()->create(['max_slots' => 2]);
        $this->fill($category, 1);
        $this->fill($category, 1, 'rejected');

        $this->post(route('registration.store'), $this->payload($this->participants($category, 1)))
            ->assertRedirectContains(route('registration.success'));
    }

    public function test_a_group_larger_than_the_remaining_slots_is_rejected_whole(): void
    {
        $category = RaceCategory::factory()->create(['max_slots' => 10]);
        $this->fill($category, 6);

        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 5)))
            ->assertSessionHasErrors('participants.0.race_category_id');

        // Nobody from the group gets in, rather than the first four squeezing through.
        $this->assertSame(6, Registration::count());
    }

    public function test_a_full_category_shows_slots_full_on_the_form(): void
    {
        $category = RaceCategory::factory()->create(['max_slots' => 1]);
        $this->fill($category, 1);

        $this->get(route('registration.create'))
            ->assertOk()
            ->assertSee('Slots full');
    }
}

<?php

namespace Tests\Feature;

use App\Models\DiscountCode;
use App\Models\PaymentProof;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\RegistrationGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\BuildsRegistrationPayloads;
use Tests\TestCase;

class IndividualRegistrationTest extends TestCase
{
    use RefreshDatabase;
    use BuildsRegistrationPayloads;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        Mail::fake();
    }

    public function test_the_page_renders_one_card_and_offers_the_group_route(): void
    {
        RaceCategory::factory()->create();

        $this->get(route('registration.create'))
            ->assertOk()
            ->assertSee('Registering as a group?')
            ->assertSee(route('registration.group.create'))
            ->assertSee('Discount Code');
    }

    public function test_a_solo_registration_creates_no_group(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $this->post(route('registration.store'), $this->payload($this->participants($category, 1)))
            ->assertRedirectContains(route('registration.success'));

        // Groups start at five people, so a lone runner is just a registration.
        $this->assertSame(0, RegistrationGroup::count());

        $registration = Registration::sole();
        $this->assertNull($registration->registration_group_id);
        $this->assertEquals(3000, $registration->price_paid);
        $this->assertNull($registration->discount_amount);
        $this->assertSame('payment_submitted', $registration->status);
        $this->assertSame('pending', $registration->paymentProof->status);
        $this->assertCount(1, Storage::disk('s3')->allFiles('payment_proofs'));
    }

    public function test_a_discount_code_applies(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $code = DiscountCode::factory()->forCategories($category)->create([
            'code'                => 'SOLO15',
            'discount_percentage' => 15,
        ]);

        $this->post(route('registration.store'), $this->payload(
            $this->participants($category, 1),
            ['discount_code' => 'SOLO15']
        ))->assertRedirectContains(route('registration.success'));

        $this->assertSame(0, RegistrationGroup::count());

        $registration = Registration::sole();
        $this->assertEquals(2550, $registration->price_paid);
        $this->assertEquals(450, $registration->discount_amount);
        $this->assertSame($code->id, $registration->discount_code_id);
        $this->assertSame(1, $code->fresh()->used_count);
    }

    public function test_a_code_for_another_category_is_rejected(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $other    = RaceCategory::factory()->priced(3000)->create();

        DiscountCode::factory()->forCategories($other)->create(['code' => 'WRONGCAT']);

        $this->post(route('registration.store'), $this->payload(
            $this->participants($category, 1),
            ['discount_code' => 'WRONGCAT']
        ))->assertSessionHasErrors('discount_code');

        $this->assertSame(0, Registration::count());
    }

    public function test_one_per_email_blocks_a_repeat_use(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $code = DiscountCode::factory()->forCategories($category)->create([
            'code'             => 'ONCEONLY',
            'one_per_email'    => true,
        ]);

        Registration::factory()->create([
            'race_category_id' => $category->id,
            'email'            => 'runner1@example.com',
            'discount_code_id' => $code->id,
        ]);

        $this->post(route('registration.store'), $this->payload(
            $this->participants($category, 1),
            ['discount_code' => 'ONCEONLY']
        ))->assertSessionHasErrors('discount_code');

        $this->assertSame(1, Registration::count()); // only the pre-existing one
        $this->assertSame(0, $code->fresh()->used_count);
    }

    public function test_more_than_one_participant_is_rejected(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        // The individual page posts exactly one participant; anything more belongs
        // on the group page.
        $this->post(route('registration.store'), $this->payload($this->participants($category, 2)))
            ->assertSessionHasErrors('participants');

        $this->assertSame(0, Registration::count());
    }

    public function test_a_validation_failure_creates_nothing_and_returns_input(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $participants = $this->participants($category, 1);
        $participants[0]['email'] = 'not-an-email';

        $this->post(route('registration.store'), $this->payload($participants))
            ->assertSessionHasErrors('participants.0.email');

        $this->assertSame(0, Registration::count());
        $this->assertSame(0, RegistrationGroup::count());
        $this->assertSame(0, PaymentProof::count());
        $this->assertSame('Runner1', session()->getOldInput('participants')[0]['first_name']);
    }

    public function test_the_quote_endpoint_prices_a_single_entry(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        DiscountCode::factory()->forCategories($category)->create([
            'code'                => 'QUOTE10',
            'discount_percentage' => 10,
        ]);

        $this->postJson(route('registration.validateDiscount'), [
            'code'             => 'QUOTE10',
            'race_category_id' => $category->id,
        ])->assertOk()->assertJson([
            'valid'             => true,
            'percentage'        => 10,
            'race_category_ids' => [$category->id],
            'base_price'        => 3000,
            'discount_amount'   => 300,
            'total'             => 2700,
        ]);
    }

    public function test_the_success_page_stays_singular_and_shows_no_reference(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $redirect = $this->post(route('registration.store'), $this->payload($this->participants($category, 1)));

        // Reference codes identify a group transaction; a lone runner has none.
        $this->followRedirects($redirect)
            ->assertOk()
            ->assertSee("You're registered!")
            ->assertDontSee('Reference Code')
            ->assertDontSee('registrations have been submitted');
    }
}

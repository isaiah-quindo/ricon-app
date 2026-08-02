<?php

namespace Tests\Feature;

use App\Models\DiscountCode;
use App\Models\PaymentProof;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\RegistrationGroup;
use App\Mail\GroupRegistrationSummary;
use App\Mail\RegistrationApproved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\BuildsRegistrationPayloads;
use Tests\TestCase;

class GroupRegistrationTest extends TestCase
{
    use RefreshDatabase;
    use BuildsRegistrationPayloads;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        Mail::fake();
    }

    public function test_the_page_opens_with_five_cards_and_no_discount_code_field(): void
    {
        RaceCategory::factory()->create();

        $response = $this->get(route('registration.group.create'))->assertOk();

        $response->assertSee('How group registration works');
        $response->assertSee(route('registration.create'));
        // The group page must not offer a code anywhere.
        $response->assertDontSee('Discount Code');
        $response->assertDontSee('name="discount_code"', false);
        // Five blank cards are seeded client-side.
        $response->assertSee('"initialParticipants":5', false);
    }

    public function test_five_participants_earn_a_five_percent_discount(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 5)))
            ->assertRedirectContains(route('registration.success'));

        $group = RegistrationGroup::sole();

        $this->assertSame(5, $group->participant_count);
        $this->assertSame('group', $group->discount_source);
        $this->assertEquals(5, $group->group_discount_percentage);
        $this->assertEquals(15000, $group->subtotal);
        $this->assertEquals(750, $group->discount_total);
        $this->assertEquals(14250, $group->total_due);

        $this->assertCount(5, Registration::all());
        Registration::each(function (Registration $r) {
            $this->assertEquals(2850, $r->price_paid);
            $this->assertEquals(150, $r->discount_amount);
            $this->assertNull($r->discount_code_id);
        });
    }

    public function test_ten_participants_earn_a_ten_percent_discount(): void
    {
        $category = RaceCategory::factory()->priced(2500)->create();

        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 10)))
            ->assertRedirectContains(route('registration.success'));

        $group = RegistrationGroup::sole();

        $this->assertEquals(10, $group->group_discount_percentage);
        $this->assertEquals(25000, $group->subtotal);
        $this->assertEquals(2500, $group->discount_total);
        $this->assertEquals(22500, $group->total_due);
    }

    public function test_tier_thresholds_are_minimums_not_exact_matches(): void
    {
        $category = RaceCategory::factory()->priced(1000)->create();

        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 7)))
            ->assertRedirectContains(route('registration.success'));

        // Seven sits in the 5-9 band, so 5% rather than nothing.
        $this->assertEquals(5, RegistrationGroup::sole()->group_discount_percentage);
    }

    public function test_a_group_below_the_minimum_is_rejected(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 4)))
            ->assertSessionHasErrors('participants');

        $this->assertSame(0, Registration::count());
        $this->assertSame(0, RegistrationGroup::count());
    }

    public function test_a_submitted_discount_code_is_ignored(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $code = DiscountCode::factory()->forCategories($category)->create([
            'code'                => 'SNEAKY50',
            'discount_percentage' => 50,
        ]);

        // The group page has no code field, so a hand-crafted one must not change the price.
        $this->post(route('registration.group.store'), $this->payload(
            $this->participants($category, 5),
            ['discount_code' => 'SNEAKY50']
        ))->assertRedirectContains(route('registration.success'));

        $group = RegistrationGroup::sole();
        $this->assertSame('group', $group->discount_source);
        $this->assertNull($group->discount_code_id);
        $this->assertEquals(750, $group->discount_total);
        $this->assertSame(0, $code->fresh()->used_count);
        $this->assertSame(0, Registration::whereNotNull('discount_code_id')->count());
    }

    public function test_participants_may_race_different_categories(): void
    {
        $short = RaceCategory::factory()->priced(2500)->create();
        $long  = RaceCategory::factory()->priced(7900)->create();

        $participants = array_merge(
            [$this->participant($long, 1)],
            collect(range(2, 5))->map(fn ($n) => $this->participant($short, $n))->all(),
        );

        $this->post(route('registration.group.store'), $this->payload($participants))
            ->assertRedirectContains(route('registration.success'));

        $group = RegistrationGroup::sole();
        $this->assertEquals(17900, $group->subtotal);          // 7900 + 4 × 2500
        $this->assertEquals(895, $group->discount_total);      // 5% of the subtotal

        // Each row is discounted against its own category price.
        $this->assertEquals(7505, Registration::where('email', 'runner1@example.com')->sole()->price_paid);
        $this->assertEquals(2375, Registration::where('email', 'runner2@example.com')->sole()->price_paid);

        // The rows add back up to the group total with no rounding remainder.
        $this->assertEquals($group->total_due, Registration::sum('price_paid'));
    }

    public function test_the_group_shares_a_single_uploaded_proof_of_payment(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 5)))
            ->assertRedirectContains(route('registration.success'));

        $proofs = PaymentProof::all();

        // A row per registration so per-person approve/reject still works...
        $this->assertCount(5, $proofs);
        // ...but they all point at the one uploaded file.
        $this->assertCount(1, $proofs->pluck('image_path')->unique());
        $this->assertCount(1, Storage::disk('s3')->allFiles('payment_proofs'));
    }

    public function test_party_size_is_capped(): void
    {
        $category = RaceCategory::factory()->priced(1000)->create();

        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 21)))
            ->assertSessionHasErrors('participants');

        $this->assertSame(0, Registration::count());
    }

    public function test_a_validation_failure_creates_nothing_and_returns_all_input(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $participants = $this->participants($category, 5);
        $participants[2]['email'] = 'not-an-email';

        $this->post(route('registration.group.store'), $this->payload($participants))
            ->assertSessionHasErrors('participants.2.email');

        $this->assertSame(0, Registration::count());
        $this->assertSame(0, RegistrationGroup::count());
        $this->assertSame(0, PaymentProof::count());

        $this->assertCount(5, session()->getOldInput('participants'));
        $this->assertSame('Runner1', session()->getOldInput('participants')[0]['first_name']);
    }

    public function test_the_success_page_shows_the_reference_code_and_group_copy(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $redirect = $this->post(route('registration.group.store'), $this->payload($this->participants($category, 6)));
        $reference = RegistrationGroup::sole()->reference_code;

        $this->followRedirects($redirect)
            ->assertOk()
            ->assertSee($reference)
            ->assertSee("You're all registered!")
            ->assertSee('All 6 registrations have been submitted');
    }

    public function test_refreshing_the_success_page_keeps_the_reference_code(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 6)));

        $group = RegistrationGroup::sole();

        // A reload has no flash, so the ?ref= in the redirect URL has to carry it.
        $this->get(route('registration.success', ['ref' => $group->reference_code]))
            ->assertOk()
            ->assertSee($group->reference_code)
            ->assertSee("You're all registered!");
    }

    public function test_an_unknown_reference_is_not_echoed_back(): void
    {
        $this->get(route('registration.success', ['ref' => 'GRP-DOESNOTEXIST']))
            ->assertOk()
            ->assertDontSee('GRP-DOESNOTEXIST')
            ->assertDontSee('Reference Code');
    }

    public function test_admin_screens_surface_the_group(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 5)));

        $group  = RegistrationGroup::sole();
        $member = $group->registrations()->first();
        $admin  = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.registrations.index'))
            ->assertOk()
            ->assertSee($group->reference_code);

        $this->actingAs($admin)->get(route('admin.registrations.index', ['group' => $group->reference_code]))
            ->assertOk()
            ->assertSee('5 results');

        // "Group registrations only" excludes solo signups.
        Registration::factory()->create(['race_category_id' => $category->id]);
        $this->actingAs($admin)->get(route('admin.registrations.index', ['group' => 'any']))
            ->assertOk()
            ->assertSee('5 results');

        $this->actingAs($admin)->get(route('admin.registrations.show', $member))
            ->assertOk()
            ->assertSee($group->reference_code)
            ->assertSee('Open group transaction');

        $csv = $this->actingAs($admin)->get(route('admin.registrations.export'))->streamedContent();
        $this->assertStringContainsString('group_reference', $csv);
        $this->assertStringContainsString($group->reference_code, $csv);
    }

    public function test_approving_a_group_confirms_every_member(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->post(route('registration.group.store'), $this->payload($this->participants($category, 6)));

        $group = RegistrationGroup::sole();
        $admin = User::factory()->create(['role' => 'admin']);

        // Approval is gated on the payment being recorded first.
        $this->actingAs($admin)
            ->post(route('admin.registration-groups.markPaid', $group), ['amount_received' => $group->total_due]);

        $this->actingAs($admin)
            ->post(route('admin.registration-groups.approveAll', $group))
            ->assertRedirect();

        $this->assertSame(6, Registration::where('status', 'approved')->count());
        $this->assertSame(6, Registration::whereNotNull('bib_number')->distinct()->count('bib_number'));
        // Six participant confirmations, plus one aggregated recap for the organizer.
        Mail::assertSent(RegistrationApproved::class, 6);
        Mail::assertSent(GroupRegistrationSummary::class, 1);
    }
}

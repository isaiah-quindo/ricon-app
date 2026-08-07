<?php

namespace Tests\Feature;

use App\Models\DiscountCode;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\BuildsRegistrationPayloads;
use Tests\TestCase;

/** A discount code can cover several race categories rather than exactly one. */
class MultiCategoryDiscountCodeTest extends TestCase
{
    use RefreshDatabase;
    use BuildsRegistrationPayloads;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        Mail::fake();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_a_code_applies_to_every_category_it_covers(): void
    {
        $hundred = RaceCategory::factory()->priced(7000)->create(['name' => 'TGC 100']);
        $sixty   = RaceCategory::factory()->priced(5000)->create(['name' => 'TGC60']);

        DiscountCode::factory()->forCategories($hundred, $sixty)->create([
            'code'                => 'BOTH20',
            'discount_percentage' => 20,
        ]);

        // A 100K entrant gets 20% off...
        $this->post(route('registration.store'), $this->payload(
            [$this->participant($hundred, 1)],
            ['discount_code' => 'BOTH20']
        ))->assertRedirectContains(route('registration.success'));

        $this->assertEquals(5600, Registration::sole()->price_paid);

        // ...and so does a 60K entrant, from the same code.
        $this->post(route('registration.store'), $this->payload(
            [$this->participant($sixty, 2)],
            ['discount_code' => 'BOTH20']
        ))->assertRedirectContains(route('registration.success'));

        $this->assertEquals(4000, Registration::where('email', 'runner2@example.com')->sole()->price_paid);
        $this->assertSame(2, DiscountCode::sole()->used_count);
    }

    public function test_a_code_is_rejected_for_a_category_it_does_not_cover(): void
    {
        $covered   = RaceCategory::factory()->priced(7000)->create();
        $uncovered = RaceCategory::factory()->priced(2500)->create();

        DiscountCode::factory()->forCategories($covered)->create(['code' => 'ONLYLONG']);

        $this->post(route('registration.store'), $this->payload(
            [$this->participant($uncovered, 1)],
            ['discount_code' => 'ONLYLONG']
        ))->assertSessionHasErrors('discount_code');

        $this->assertSame(0, Registration::count());
    }

    public function test_a_code_covering_no_categories_never_applies(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        DiscountCode::factory()->create(['code' => 'ORPHAN']);   // no categories attached

        $this->post(route('registration.store'), $this->payload(
            [$this->participant($category, 1)],
            ['discount_code' => 'ORPHAN']
        ))->assertSessionHasErrors('discount_code');
    }

    public function test_the_quote_endpoint_returns_every_covered_category(): void
    {
        $a = RaceCategory::factory()->priced(7000)->create();
        $b = RaceCategory::factory()->priced(5000)->create();
        $c = RaceCategory::factory()->priced(2500)->create();

        DiscountCode::factory()->forCategories($a, $b)->create([
            'code'                => 'TWOCATS',
            'discount_percentage' => 10,
        ]);

        $response = $this->postJson(route('registration.validateDiscount'), [
            'code'             => 'TWOCATS',
            'race_category_id' => $a->id,
        ])->assertOk();

        // The form uses this list to keep the total right if the runner switches distance.
        $ids = $response->json('race_category_ids');
        sort($ids);
        $expected = [$a->id, $b->id];
        sort($expected);
        $this->assertSame($expected, $ids);
        $this->assertNotContains($c->id, $ids);
    }

    public function test_the_model_reports_which_categories_it_applies_to(): void
    {
        $a = RaceCategory::factory()->create();
        $b = RaceCategory::factory()->create();
        $c = RaceCategory::factory()->create();

        $code = DiscountCode::factory()->forCategories($a, $b)->create();

        $this->assertTrue($code->appliesTo($a->id));
        $this->assertTrue($code->appliesTo($b->id));
        $this->assertFalse($code->appliesTo($c->id));
        $this->assertFalse($code->appliesTo(null));
    }

    // ------------------------------------------------------------------
    // Admin
    // ------------------------------------------------------------------

    public function test_an_admin_can_create_a_code_covering_several_categories(): void
    {
        $a = RaceCategory::factory()->create();
        $b = RaceCategory::factory()->create();

        $this->actingAs($this->admin())
            ->post(route('admin.discount-codes.store'), [
                'code'                => 'multi25',
                'race_category_ids'   => [$a->id, $b->id],
                'discount_percentage' => 25,
                'is_active'           => 1,
            ])
            ->assertRedirect(route('admin.discount-codes.index'));

        $code = DiscountCode::sole();
        $this->assertSame('MULTI25', $code->code);
        $this->assertEqualsCanonicalizing([$a->id, $b->id], $code->raceCategoryIds());
    }

    public function test_creating_a_code_requires_at_least_one_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.discount-codes.store'), [
                'code'                => 'NOCATS',
                'discount_percentage' => 10,
            ])
            ->assertSessionHasErrors('race_category_ids');

        $this->assertSame(0, DiscountCode::count());
    }

    public function test_editing_a_code_replaces_its_categories(): void
    {
        $a = RaceCategory::factory()->create();
        $b = RaceCategory::factory()->create();
        $c = RaceCategory::factory()->create();

        $code = DiscountCode::factory()->forCategories($a, $b)->create(['code' => 'SWAP']);

        $this->actingAs($this->admin())
            ->put(route('admin.discount-codes.update', $code), [
                'code'                => 'SWAP',
                'race_category_ids'   => [$c->id],
                'discount_percentage' => 15,
                'is_active'           => 1,
            ])
            ->assertRedirect(route('admin.discount-codes.index'));

        // sync() replaces rather than adds.
        $this->assertSame([$c->id], $code->fresh()->raceCategoryIds());
    }

    public function test_the_index_lists_the_covered_categories(): void
    {
        $a = RaceCategory::factory()->create(['name' => 'TGC 100']);
        $b = RaceCategory::factory()->create(['name' => 'TGC60']);
        RaceCategory::factory()->create(['name' => 'TGC10']);   // left uncovered

        $code = DiscountCode::factory()->forCategories($a, $b)->create(['code' => 'SHOWN']);

        $this->actingAs($this->admin())->get(route('admin.discount-codes.index'))
            ->assertOk()
            ->assertSee('TGC 100')
            ->assertSee('TGC60')
            ->assertDontSee('All categories');

        $this->actingAs($this->admin())->get(route('admin.discount-codes.edit', $code))
            ->assertOk()
            ->assertSee('Race Categories')
            ->assertSee('name="race_category_ids[]"', false);
    }

    public function test_the_index_collapses_full_coverage_into_one_pill(): void
    {
        $categories = RaceCategory::factory()->count(3)->create();
        DiscountCode::factory()->forCategories($categories)->create(['code' => 'EVERYTHING']);

        // Listing every category name would be noise once a code covers them all.
        $this->actingAs($this->admin())->get(route('admin.discount-codes.index'))
            ->assertOk()
            ->assertSee('All categories');
    }
}

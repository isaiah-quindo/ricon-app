<?php

namespace Tests\Feature;

use App\Models\PaymentProof;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/** Upgrading or downgrading a runner: new bib in the new category, no duplicates, payment added to revenue. */
class AdminChangeCategoryTest extends TestCase
{
    use RefreshDatabase;

    private RaceCategory $sixty;
    private RaceCategory $hundred;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->sixty = RaceCategory::factory()->priced(1000)->create(['name' => '60K', 'bib_start_number' => 60]);
        $this->hundred = RaceCategory::factory()->priced(2000)->create(['name' => '100K', 'bib_start_number' => 100]);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'name' => 'Race Admin']);
    }

    private function runner(RaceCategory $category, ?int $bib, array $attributes = []): Registration
    {
        $factory = Registration::factory();

        if ($bib !== null) {
            $factory = $factory->approved();
        }

        return $factory->create(array_merge([
            'race_category_id' => $category->id,
            'bib_number'       => $bib,
            'price_paid'       => $category->price,
        ], $attributes));
    }

    private function move(Registration $registration, RaceCategory $to, $amount, ?string $note = null)
    {
        return $this->actingAs($this->admin())
            ->from(route('admin.registrations.show', $registration))
            ->patch(route('admin.registrations.changeCategory', $registration), [
                'race_category_id' => $to->id,
                'amount_added'     => $amount,
                'note'             => $note,
            ]);
    }

    public function test_upgrade_gives_the_next_highest_bib_in_the_new_category_and_adds_the_payment(): void
    {
        // 60K runners 1..6, ours is 60-004. 100K already has bibs 1..12.
        foreach ([1, 2, 3, 5, 6] as $bib) {
            $this->runner($this->sixty, $bib);
        }
        foreach (range(1, 12) as $bib) {
            $this->runner($this->hundred, $bib);
        }
        $runner = $this->runner($this->sixty, 4);
        $this->assertSame('60-004', $runner->formatted_bib);

        $this->move($runner, $this->hundred, 1000, 'GCash ref 1234')
            ->assertRedirect(route('admin.registrations.show', $runner))
            ->assertSessionHas('success');

        $runner->refresh();

        $this->assertSame($this->hundred->id, $runner->race_category_id);
        $this->assertSame(13, (int) $runner->bib_number);
        $this->assertSame('100-013', $runner->formatted_bib);
        $this->assertEquals(2000, $runner->price_paid);
        $this->assertSame('approved', $runner->status);

        $lines = explode("\n", $runner->admin_notes);
        $this->assertMatchesRegularExpression('/^Category change · .+ · Race Admin$/', $lines[0]);
        $this->assertSame([
            'Category: 60K → 100K',
            'Bib: 60-004 → 100-013',
            'Paid: ₱1,000.00 → ₱2,000.00 (+₱1,000.00)',
            'Note: GCash ref 1234',
        ], array_slice($lines, 1));

        // Nobody in 100K shares the new bib, and the next 60K approval doesn't take 60-004.
        $this->assertSame(1, Registration::where('race_category_id', $this->hundred->id)->where('bib_number', 13)->count());
        $next = $this->runner($this->sixty, null);
        $next->assignBibNumber();
        $this->assertSame(7, (int) $next->bib_number);
    }

    public function test_detail_page_shows_the_change_category_form_with_other_categories_only(): void
    {
        $runner = $this->runner($this->sixty, 4);

        $this->actingAs($this->admin())
            ->get(route('admin.registrations.show', $runner))
            ->assertOk()
            ->assertSee('Change Category')
            ->assertSee(route('admin.registrations.changeCategory', $runner))
            ->assertSee('100K · ₱2,000.00')
            ->assertDontSee('60K · ₱1,000.00');
    }

    public function test_each_change_is_its_own_entry_in_the_admin_notes(): void
    {
        $runner = $this->runner($this->sixty, 4, ['admin_notes' => 'Called runner about shirt size.']);

        $this->move($runner, $this->hundred, 1000, 'Upgrade');
        $this->move($runner->fresh(), $this->sixty, 0, 'Changed mind');

        $blocks = explode("\n\n", $runner->fresh()->admin_notes);
        $this->assertCount(3, $blocks);
        $this->assertSame('Called runner about shirt size.', $blocks[0]);
        $this->assertStringContainsString("Category: 60K → 100K\n", $blocks[1]);
        $this->assertStringContainsString("Category: 100K → 60K\n", $blocks[2]);
        $this->assertStringContainsString('Paid: ₱2,000.00 → ₱2,000.00 (+₱0.00)', $blocks[2]);

        $this->actingAs($this->admin())
            ->get(route('admin.registrations.show', $runner))
            ->assertOk()
            ->assertSeeInOrder([
                'Called runner about shirt size.',
                'Category change', 'Category', '60K → 100K', 'Bib', '60-004 → 100-001', 'Note', 'Upgrade',
                'Category change', 'Category', '100K → 60K', 'Note', 'Changed mind',
            ]);
    }

    public function test_notes_saved_in_the_earlier_one_line_format_are_shown_as_entries_too(): void
    {
        $runner = $this->runner($this->sixty, 4, [
            'admin_notes' => "Called about shirt.\n[Oct 1, 2026] Category changed TGC 21 → TGC 60. Bib 21-001 → 60-004. "
                . 'Paid ₱2,850.00 → ₱3,350.00 (+₱500.00) by Isaiah Quindo. Note: Upgraded from 21 to 60k',
        ]);

        $this->assertSame([
            ['type' => 'text', 'text' => 'Called about shirt.'],
            [
                'type'  => 'change',
                'title' => 'Category change',
                'meta'  => 'Oct 1, 2026 · Isaiah Quindo',
                'rows'  => [
                    'Category' => 'TGC 21 → TGC 60',
                    'Bib'      => '21-001 → 60-004',
                    'Paid'     => '₱2,850.00 → ₱3,350.00 (+₱500.00)',
                    'Note'     => 'Upgraded from 21 to 60k',
                ],
            ],
        ], $runner->adminNoteEntries());

        $this->actingAs($this->admin())
            ->get(route('admin.registrations.show', $runner))
            ->assertOk()
            ->assertSeeInOrder(['Category change', 'Oct 1, 2026 · Isaiah Quindo', 'Category', 'TGC 21 → TGC 60', 'Bib', '21-001 → 60-004'])
            ->assertDontSee('[Oct 1, 2026] Category changed');
    }

    public function test_upgrade_into_an_empty_category_starts_at_bib_one(): void
    {
        $runner = $this->runner($this->sixty, 4);

        $this->move($runner, $this->hundred, 1000);

        $this->assertSame('100-001', $runner->fresh()->formatted_bib);
    }

    public function test_downgrade_keeps_the_amount_paid(): void
    {
        $runner = $this->runner($this->hundred, 3);

        $this->move($runner, $this->sixty, 0)->assertSessionHasNoErrors();

        $runner->refresh();
        $this->assertSame($this->sixty->id, $runner->race_category_id);
        $this->assertEquals(2000, $runner->price_paid);
        $this->assertSame('60-001', $runner->formatted_bib);
    }

    public function test_negative_amounts_are_rejected(): void
    {
        $runner = $this->runner($this->hundred, 3);

        $this->move($runner, $this->sixty, -1000)->assertSessionHasErrors('amount_added');

        $runner->refresh();
        $this->assertSame($this->hundred->id, $runner->race_category_id);
        $this->assertEquals(2000, $runner->price_paid);
        $this->assertSame(3, (int) $runner->bib_number);
    }

    public function test_runner_without_a_bib_moves_and_gets_a_bib_in_the_new_category_on_approval(): void
    {
        $this->runner($this->hundred, 5);
        $runner = $this->runner($this->sixty, null);
        PaymentProof::create(['registration_id' => $runner->id, 'image_path' => 'payment_proofs/x.png', 'status' => 'pending']);

        $this->move($runner, $this->hundred, 1000);

        $runner->refresh();
        $this->assertSame($this->hundred->id, $runner->race_category_id);
        $this->assertNull($runner->bib_number);
        $this->assertStringNotContainsString('Bib:', $runner->admin_notes);

        $this->actingAs($this->admin())->post(route('admin.registrations.approve', $runner));

        $this->assertSame('100-006', $runner->fresh()->formatted_bib);
    }

    public function test_missing_price_paid_falls_back_to_the_old_category_price(): void
    {
        $runner = $this->runner($this->sixty, 1, ['price_paid' => null]);

        $this->move($runner, $this->hundred, 1000);

        $this->assertEquals(2000, $runner->fresh()->price_paid);
    }

    public function test_same_category_is_rejected(): void
    {
        $runner = $this->runner($this->sixty, 4);

        $this->move($runner, $this->sixty, 0)->assertSessionHasErrors('race_category_id');

        $this->assertSame(4, (int) $runner->fresh()->bib_number);
    }

    public function test_rejected_registrations_cannot_change_category(): void
    {
        $runner = $this->runner($this->sixty, null, ['status' => 'rejected']);

        $this->move($runner, $this->hundred, 1000)->assertSessionHas('error');

        $this->assertSame($this->sixty->id, $runner->fresh()->race_category_id);
    }

    public function test_dashboard_revenue_reflects_the_upgrade(): void
    {
        $runner = $this->runner($this->sixty, 4);

        $this->move($runner, $this->hundred, 1000);

        $response = $this->actingAs($this->admin())->get(route('admin.dashboard'))->assertOk();

        $this->assertEquals(2000, $response->viewData('stats')['revenue']);
        $byCategory = $response->viewData('byCategory')->keyBy('id');
        $this->assertEquals(2000, $byCategory[$this->hundred->id]->approved_revenue);
        $this->assertEquals(0, $byCategory[$this->sixty->id]->approved_revenue ?? 0);
    }

    public function test_manual_bib_update_refuses_a_bib_taken_in_the_same_category(): void
    {
        $this->runner($this->hundred, 13, ['first_name' => 'Ana', 'last_name' => 'Reyes']);
        $runner = $this->runner($this->hundred, 2);

        $this->actingAs($this->admin())
            ->from(route('admin.registrations.show', $runner))
            ->patch(route('admin.registrations.updateBib', $runner), ['bib_number' => 13])
            ->assertSessionHasErrors(['bib_number' => 'Bib 100-013 is already taken by Ana Reyes.']);

        $this->assertSame(2, (int) $runner->fresh()->bib_number);
    }

    public function test_manual_bib_update_allows_the_same_number_in_another_category(): void
    {
        $this->runner($this->sixty, 13);
        $runner = $this->runner($this->hundred, 2);

        $this->actingAs($this->admin())
            ->patch(route('admin.registrations.updateBib', $runner), ['bib_number' => 13])
            ->assertSessionHasNoErrors();

        $this->assertSame(13, (int) $runner->fresh()->bib_number);
    }

    public function test_bib_assigned_before_approval_is_kept_on_approval(): void
    {
        $this->runner($this->hundred, 100);
        $runner = $this->runner($this->hundred, null);
        PaymentProof::create(['registration_id' => $runner->id, 'image_path' => 'payment_proofs/x.png', 'status' => 'pending']);

        $this->actingAs($this->admin())
            ->patch(route('admin.registrations.updateBib', $runner), ['bib_number' => 50])
            ->assertSessionHasNoErrors();

        $this->actingAs($this->admin())->post(route('admin.registrations.approve', $runner));

        $runner->refresh();
        $this->assertSame('approved', $runner->status);
        $this->assertSame('100-050', $runner->formatted_bib);
    }

    public function test_approval_without_a_preassigned_bib_takes_the_next_highest(): void
    {
        $this->runner($this->hundred, 100);
        $runner = $this->runner($this->hundred, null);
        PaymentProof::create(['registration_id' => $runner->id, 'image_path' => 'payment_proofs/x.png', 'status' => 'pending']);

        $this->actingAs($this->admin())->post(route('admin.registrations.approve', $runner));

        $this->assertSame('100-101', $runner->fresh()->formatted_bib);
    }

    public function test_non_admins_cannot_change_category(): void
    {
        $runner = $this->runner($this->sixty, 4);

        $this->actingAs(User::factory()->create())
            ->patch(route('admin.registrations.changeCategory', $runner), [
                'race_category_id' => $this->hundred->id,
                'amount_added'     => 1000,
            ])
            ->assertForbidden();

        $this->assertSame($this->sixty->id, $runner->fresh()->race_category_id);
    }
}

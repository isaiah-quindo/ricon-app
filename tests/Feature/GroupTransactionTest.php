<?php

namespace Tests\Feature;

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

/** Tracking a group as a transaction: who booked it, how, when, and what was received. */
class GroupTransactionTest extends TestCase
{
    use RefreshDatabase;
    use BuildsRegistrationPayloads;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        Mail::fake();
    }

    private function submitGroup(RaceCategory $category, int $count = 5, array $overrides = [])
    {
        return $this->post(route('registration.group.store'), $this->payload(
            $this->participants($category, $count),
            $overrides,
        ));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_the_organizer_is_recorded_on_the_group(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $this->submitGroup($category)->assertRedirectContains(route('registration.success'));

        $group = RegistrationGroup::sole();

        // The coordinator is not one of the runners, which is the whole point.
        $this->assertSame('Coach Reyes', $group->organizer_name);
        $this->assertSame('coach@club.example', $group->organizer_email);
        $this->assertSame('+63 917 111 2222', $group->organizer_mobile);
        $this->assertSame('Baguio Trail Club', $group->organizer_team);
        $this->assertSame('runner1@example.com', $group->leader_email);
        $this->assertSame(0, Registration::where('email', 'coach@club.example')->count());
    }

    public function test_the_organizer_is_required(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $payload = $this->payload($this->participants($category, 5));
        unset($payload['organizer']);

        $this->post(route('registration.group.store'), $payload)
            ->assertSessionHasErrors(['organizer.name', 'organizer.email', 'organizer.mobile']);

        $this->assertSame(0, RegistrationGroup::count());
    }

    public function test_the_payment_method_and_timestamp_land_on_the_group(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $this->submitGroup($category, 5, ['payment_method' => 'GCash']);

        $group = RegistrationGroup::sole();
        $this->assertSame('GCash', $group->payment_method);
        $this->assertSame('pending', $group->payment_status);
        $this->assertNotNull($group->created_at);
        $this->assertNull($group->verified_at);
        $this->assertNull($group->amount_received);
    }

    public function test_individual_registration_creates_no_group_and_keeps_its_own_payment_state(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();

        $this->post(route('registration.store'), $this->payload($this->participants($category, 1)))
            ->assertRedirectContains(route('registration.success'));

        // Nothing group-shaped exists for a lone runner, so there is no organizer or
        // group payment state to go stale. Their proof is managed per registration.
        $this->assertSame(0, RegistrationGroup::count());
        $this->assertNull(Registration::sole()->registration_group_id);
        $this->assertSame('pending', Registration::sole()->paymentProof->status);
    }

    public function test_marking_a_group_paid_stamps_the_transaction_and_verifies_every_proof(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.registration-groups.markPaid', $group), [
                'amount_received'   => 14250,
                'payment_reference' => 'RCBC-99127',
            ])
            ->assertRedirect();

        $group->refresh();
        $this->assertSame('verified', $group->payment_status);
        $this->assertEquals(14250, $group->amount_received);
        $this->assertSame('RCBC-99127', $group->payment_reference);
        $this->assertNotNull($group->verified_at);
        $this->assertSame($admin->id, $group->verified_by);
        $this->assertFalse($group->hasPaymentDiscrepancy());

        // One transfer, so every member's copy of the shared receipt is verified.
        $this->assertSame(5, PaymentProof::where('status', 'verified')->count());
    }

    public function test_a_short_payment_is_flagged_rather_than_hidden(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.markPaid', $group), ['amount_received' => 14000]);

        $group->refresh();
        $this->assertTrue($group->hasPaymentDiscrepancy());
        $this->assertEquals(250, $group->paymentShortfall());
    }

    public function test_an_overpayment_is_reported_as_negative_shortfall(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.markPaid', $group), ['amount_received' => 14500]);

        $this->assertEquals(-250, $group->refresh()->paymentShortfall());
    }

    public function test_rejecting_a_group_payment_records_the_reason(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.rejectPayment', $group), [
                'admin_notes' => 'Receipt shows a different account.',
            ])
            ->assertRedirect();

        $group->refresh();
        $this->assertSame('rejected', $group->payment_status);
        $this->assertSame('Receipt shows a different account.', $group->admin_notes);
        $this->assertNull($group->verified_at);
        $this->assertSame(5, PaymentProof::where('status', 'rejected')->count());
    }

    public function test_group_notes_can_be_saved_on_their_own(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.updateNotes', $group), [
                'admin_notes' => 'Organizer will send the balance on Friday.',
            ])
            ->assertRedirect();

        $group->refresh();
        $this->assertSame('Organizer will send the balance on Friday.', $group->admin_notes);
        // Saving a note must not touch the payment state.
        $this->assertSame('pending', $group->payment_status);
    }

    public function test_the_admin_index_lists_groups_and_excludes_individuals(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);
        // A solo signup creates no group at all, so it cannot appear here.
        $this->post(route('registration.store'), $this->payload([$this->participant($category, 9)]));

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())->get(route('admin.registration-groups.index'))
            ->assertOk()
            ->assertSee($group->reference_code)
            ->assertSee('Coach Reyes')
            ->assertSee('Baguio Trail Club')
            ->assertSee('1 group');
    }

    public function test_the_admin_index_can_be_searched_and_filtered(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);
        $group = RegistrationGroup::sole();
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.registration-groups.index', ['search' => 'Baguio']))
            ->assertOk()->assertSee($group->reference_code);

        $this->actingAs($admin)->get(route('admin.registration-groups.index', ['search' => 'nobody']))
            ->assertOk()->assertDontSee($group->reference_code);

        $this->actingAs($admin)->get(route('admin.registration-groups.index', ['payment_status' => 'verified']))
            ->assertOk()->assertDontSee($group->reference_code);

        $this->actingAs($admin)->get(route('admin.registration-groups.index', ['payment_status' => 'pending']))
            ->assertOk()->assertSee($group->reference_code);
    }

    public function test_the_admin_detail_page_shows_the_transaction_and_breakdown(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create(['name' => 'TGC 100']);
        $this->submitGroup($category, 5, ['payment_method' => 'Bank Transfer']);

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())->get(route('admin.registration-groups.show', $group))
            ->assertOk()
            ->assertSee($group->reference_code)
            ->assertSee('Coach Reyes')                 // who
            ->assertSee('coach@club.example')
            ->assertSee('Bank Transfer')               // how
            ->assertSee($group->created_at->format('F j, Y'), false)  // when
            ->assertSee('Breakdown')                   // breakdown
            ->assertSee('Runner1')
            ->assertSee('TGC 100')
            ->assertSee('14,250.00')                   // total due
            ->assertSee('Record the payment first');   // approval gated
    }

    public function test_approving_from_the_group_page_confirms_every_member(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.registration-groups.markPaid', $group), ['amount_received' => 14250]);

        $this->actingAs($admin)
            ->post(route('admin.registration-groups.approveAll', $group))
            ->assertRedirect();

        $this->assertSame(5, Registration::where('status', 'approved')->count());
        $this->assertSame(5, Registration::whereNotNull('bib_number')->distinct()->count('bib_number'));
        Mail::assertSent(RegistrationApproved::class, 5);
        Mail::assertSent(GroupRegistrationSummary::class, 1);
    }

    public function test_a_group_cannot_be_approved_before_its_payment_is_recorded(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.approveAll', $group))
            ->assertRedirect()
            ->assertSessionHas('error');

        // Nobody approved, no bibs issued, nobody emailed.
        $this->assertSame(0, Registration::where('status', 'approved')->count());
        $this->assertSame(0, Registration::whereNotNull('bib_number')->count());
        Mail::assertNothingSent();
    }

    public function test_a_rejected_group_payment_still_blocks_approval(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $group = RegistrationGroup::sole();
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.registration-groups.rejectPayment', $group), [
            'admin_notes' => 'Wrong account.',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.registration-groups.approveAll', $group))
            ->assertSessionHas('error');

        $this->assertSame(0, Registration::where('status', 'approved')->count());
    }

    public function test_a_group_member_cannot_be_approved_individually_before_payment(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);

        $member = RegistrationGroup::sole()->registrations()->first();
        $admin  = $this->admin();

        // The per-registration approve button must respect the same rule.
        $this->actingAs($admin)
            ->post(route('admin.registrations.approve', $member))
            ->assertSessionHas('error');

        $this->assertSame('payment_submitted', $member->fresh()->status);
        $this->assertNull($member->fresh()->bib_number);
        Mail::assertNothingSent();
    }

    public function test_an_individual_registration_is_never_blocked_by_group_payment(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->post(route('registration.store'), $this->payload($this->participants($category, 1)));

        $registration = Registration::sole();
        $this->assertFalse($registration->isBlockedByGroupPayment());

        $this->actingAs($this->admin())
            ->post(route('admin.registrations.approve', $registration))
            ->assertSessionHas('success');

        $this->assertSame('approved', $registration->fresh()->status);
        $this->assertNotNull($registration->fresh()->bib_number);
    }

    public function test_a_party_of_one_cannot_be_opened_as_a_group(): void
    {
        // Rows like this predate groups being 5+ only. They are not transactions.
        $solo = RegistrationGroup::factory()->partyOfOne()->create();
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.registration-groups.show', $solo))->assertNotFound();
        $this->actingAs($admin)->post(route('admin.registration-groups.markPaid', $solo), ['amount_received' => 100])->assertNotFound();
        $this->actingAs($admin)->post(route('admin.registration-groups.rejectPayment', $solo), ['admin_notes' => 'x'])->assertNotFound();
        $this->actingAs($admin)->post(route('admin.registration-groups.updateNotes', $solo), ['admin_notes' => 'x'])->assertNotFound();
        $this->actingAs($admin)->post(route('admin.registration-groups.approveAll', $solo))->assertNotFound();

        // And it is absent from the list.
        $this->actingAs($admin)->get(route('admin.registration-groups.index'))
            ->assertOk()
            ->assertDontSee($solo->reference_code);
    }

    public function test_group_pages_are_admin_only(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->submitGroup($category, 5);
        $group = RegistrationGroup::sole();

        $this->get(route('admin.registration-groups.index'))->assertRedirect(route('login'));
        $this->get(route('admin.registration-groups.show', $group))->assertRedirect(route('login'));
    }
}

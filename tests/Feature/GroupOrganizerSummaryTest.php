<?php

namespace Tests\Feature;

use App\Mail\GroupRegistrationSummary;
use App\Mail\RegistrationApproved;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\RegistrationGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\BuildsRegistrationPayloads;
use Tests\TestCase;

/** The organizer's aggregated recap, sent on top of each participant's own email. */
class GroupOrganizerSummaryTest extends TestCase
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

    /** Submits a group and records its payment, leaving it ready to approve. */
    private function paidGroup(int $count = 5): RegistrationGroup
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->post(route('registration.group.store'), $this->payload($this->participants($category, $count)));

        $group = RegistrationGroup::sole();

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.markPaid', $group), ['amount_received' => $group->total_due]);

        return $group->fresh();
    }

    public function test_the_organizer_is_emailed_once_the_group_is_fully_approved(): void
    {
        $group = $this->paidGroup(5);

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.approveAll', $group))
            ->assertRedirect();

        // Each participant still gets their own confirmation...
        Mail::assertSent(RegistrationApproved::class, 5);
        // ...plus exactly one aggregated recap to the organizer.
        Mail::assertSent(GroupRegistrationSummary::class, 1);
        Mail::assertSent(
            GroupRegistrationSummary::class,
            fn ($mail) => $mail->hasTo('coach@club.example') && $mail->group->is($group)
        );

        $this->assertNotNull($group->fresh()->organizer_notified_at);
    }

    public function test_the_summary_is_not_sent_while_anyone_is_still_unresolved(): void
    {
        $group = $this->paidGroup(5);
        $admin = $this->admin();

        // Approve four of the five individually.
        foreach ($group->registrations()->take(4)->get() as $member) {
            $this->actingAs($admin)->post(route('admin.registrations.approve', $member));
        }

        Mail::assertSent(RegistrationApproved::class, 4);
        Mail::assertNotSent(GroupRegistrationSummary::class);
        $this->assertNull($group->fresh()->organizer_notified_at);
    }

    public function test_approving_the_last_member_individually_also_triggers_the_summary(): void
    {
        $group = $this->paidGroup(5);
        $admin = $this->admin();

        foreach ($group->registrations as $member) {
            $this->actingAs($admin)->post(route('admin.registrations.approve', $member));
        }

        // The bulk action is not the only path that can finish a group.
        Mail::assertSent(GroupRegistrationSummary::class, 1);
        $this->assertNotNull($group->fresh()->organizer_notified_at);
    }

    public function test_a_rejected_member_still_counts_as_resolved(): void
    {
        $group = $this->paidGroup(5);
        $admin = $this->admin();

        $members = $group->registrations;
        $this->actingAs($admin)->post(route('admin.registrations.reject', $members->first()), [
            'admin_notes' => 'Duplicate entry.',
        ]);

        Mail::assertNotSent(GroupRegistrationSummary::class);

        $this->actingAs($admin)->post(route('admin.registration-groups.approveAll', $group));

        // Four approved, one rejected — the organizer still needs the outcome.
        Mail::assertSent(GroupRegistrationSummary::class, 1);
        $this->assertSame(4, Registration::where('status', 'approved')->count());
    }

    public function test_the_summary_is_sent_only_once(): void
    {
        $group = $this->paidGroup(5);
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.registration-groups.approveAll', $group));
        // Running it again finds nobody pending and must not re-send.
        $this->actingAs($admin)->post(route('admin.registration-groups.approveAll', $group));

        Mail::assertSent(GroupRegistrationSummary::class, 1);
    }

    public function test_an_admin_can_resend_the_summary(): void
    {
        $group = $this->paidGroup(5);
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.registration-groups.approveAll', $group));
        $this->actingAs($admin)
            ->post(route('admin.registration-groups.resendSummary', $group))
            ->assertRedirect()
            ->assertSessionHas('success');

        Mail::assertSent(GroupRegistrationSummary::class, 2);
    }

    public function test_the_summary_renders_the_roster_totals_and_bibs(): void
    {
        $group = $this->paidGroup(5);
        $this->actingAs($this->admin())->post(route('admin.registration-groups.approveAll', $group));

        $group->refresh();
        $rendered = (new GroupRegistrationSummary($group))->render();

        $this->assertStringContainsString($group->reference_code, $rendered);
        $this->assertStringContainsString('Coach Reyes', $rendered);
        $this->assertStringContainsString('Runner1 Test', $rendered);
        $this->assertStringContainsString('14,250.00', $rendered);   // total
        $this->assertStringContainsString('15,000.00', $rendered);   // subtotal
        $this->assertStringContainsString('750.00', $rendered);      // group discount
        $this->assertStringContainsString('Confirmed', $rendered);

        // Every assigned bib appears, so the organizer can hand them out.
        foreach ($group->registrations as $member) {
            $this->assertStringContainsString($member->formatted_bib, $rendered);
        }
    }

    public function test_the_copy_adapts_when_nothing_is_confirmed_yet(): void
    {
        // Reachable via the admin's "Send summary now" before any approvals.
        $group = $this->paidGroup(5);

        $rendered = (new GroupRegistrationSummary($group->fresh()))->render();

        $this->assertStringContainsString('Awaiting confirmation', $rendered);
        $this->assertStringContainsString('None of the', $rendered);
        $this->assertStringNotContainsString('All 0 participants', $rendered);
    }

    public function test_the_copy_says_all_confirmed_when_everyone_is_approved(): void
    {
        $group = $this->paidGroup(5);
        $this->actingAs($this->admin())->post(route('admin.registration-groups.approveAll', $group));

        $rendered = (new GroupRegistrationSummary($group->fresh()))->render();

        $this->assertStringContainsString('All 5 Confirmed', $rendered);
        $this->assertStringNotContainsString('Awaiting confirmation', $rendered);
    }

    public function test_an_individual_registration_never_triggers_a_summary(): void
    {
        $category = RaceCategory::factory()->priced(3000)->create();
        $this->post(route('registration.store'), $this->payload($this->participants($category, 1)));

        $this->actingAs($this->admin())
            ->post(route('admin.registrations.approve', Registration::sole()))
            ->assertSessionHas('success');

        Mail::assertSent(RegistrationApproved::class, 1);
        Mail::assertNotSent(GroupRegistrationSummary::class);
    }

    public function test_a_group_without_an_organizer_email_is_skipped_rather_than_erroring(): void
    {
        // Rows created before organizer capture have no address to send to.
        $group = RegistrationGroup::factory()->create(['organizer_email' => null]);
        Registration::factory()->count(5)->create([
            'registration_group_id' => $group->id,
            'status'                => 'approved',
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.registration-groups.resendSummary', $group))
            ->assertSessionHas('error');

        Mail::assertNotSent(GroupRegistrationSummary::class);
    }
}

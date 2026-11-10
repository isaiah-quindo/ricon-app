<?php

namespace Tests\Feature;

use App\Models\CheckpointPass;
use App\Models\RaceCategory;
use App\Models\Registration;
use App\Models\User;
use App\Support\LiveTracking\Course;
use App\Support\LiveTracking\Eta;
use App\Support\LiveTracking\Format;
use App\Support\LiveTracking\LiveRace;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Live checkpoint tracking: public pages, the timing console, corrections, DNFs and ETAs. */
class LiveTrackingTest extends TestCase
{
    use RefreshDatabase;

    private RaceCategory $hundred;

    protected function setUp(): void
    {
        parent::setUp();

        // Each test starts with an empty live cache (the file store would carry over between tests).
        config(['live.cache_store' => 'array']);

        $this->hundred = RaceCategory::factory()->create(['slug' => 'tgc-100', 'bib_start_number' => 100]);
        // 100K gun is Fri Nov 13, 11 PM. Most tests happen on Saturday morning, race-day time.
        $this->travelTo(CarbonImmutable::parse('2026-11-14 10:00', 'Asia/Manila'));
    }

    private function runner(int $bib, array $attributes = []): Registration
    {
        return Registration::factory()->approved()->create([
            'race_category_id' => $this->hundred->id,
            'bib_number'       => $bib,
            ...$attributes,
        ]);
    }

    /** A pass at a race-day local time, stored the way the app stores it (UTC). */
    private function pass(Registration $runner, string $checkpoint, string $in, ?string $out = null): CheckpointPass
    {
        $at = fn ($time) => $time ? CarbonImmutable::parse($time, 'Asia/Manila')->utc() : null;

        return CheckpointPass::create([
            'registration_id' => $runner->id,
            'checkpoint'      => $checkpoint,
            'in_at'           => $at($in),
            'out_at'          => $at($out),
        ]);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    // ---------------------------------------------------------------- Public pages

    public function test_live_redirects_to_the_100k(): void
    {
        $this->get('/live')->assertRedirect('/live/100km');
    }

    public function test_runners_are_approved_registrations_with_a_bib(): void
    {
        $this->runner(7, ['first_name' => 'Maria', 'last_name' => 'Santos']);
        Registration::factory()->create(['race_category_id' => $this->hundred->id, 'bib_number' => 8]); // not approved
        Registration::factory()->approved()->create(['race_category_id' => $this->hundred->id]); // no bib yet

        $race = LiveRace::load('100km');

        $this->assertSame(['100-007'], array_keys($race->runners));
        $this->assertSame('Maria Santos', $race->runners['100-007']['name']);
    }

    public function test_race_centre_shows_recorded_arrivals(): void
    {
        $maria = $this->runner(7, ['first_name' => 'Maria', 'last_name' => 'Santos']);
        $this->pass($maria, 'as1', '2026-11-14 00:30', '2026-11-14 00:40');
        $this->pass($maria, 'as2', '2026-11-14 03:10');

        $this->get('/live/100km')
            ->assertOk()
            ->assertSee('TGC 100 KM')
            ->assertSee('Maria Santos')
            ->assertSee('3:10 AM')
            ->assertSee('At aid station');
    }

    public function test_before_the_gun_nobody_has_started(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-12 09:00', 'Asia/Manila'));
        $this->runner(1);

        $race = LiveRace::load('100km');

        $this->assertSame(0, $race->stats()['race_seconds']);
        $this->assertSame('not_started', $race->runners['100-001']['status']);
        $this->assertNull($race->runners['100-001']['position']);
        $this->get('/live/100km')->assertOk()->assertSee('No arrivals yet.');
    }

    public function test_untracked_or_unknown_races_404(): void
    {
        $this->get('/live/21km')->assertNotFound();
        $this->get('/live/50km')->assertNotFound();
    }

    public function test_cp_query_presses_that_checkpoint_card(): void
    {
        $html = $this->get('/live/100km?cp=as6')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'aria-pressed="true"'));
        $this->assertMatchesRegularExpression('/aria-pressed="true".{0,800}?AS6/s', $html);

        $html = $this->get('/live/100km?cp=nope')->getContent();
        $this->assertMatchesRegularExpression('/aria-pressed="true".{0,800}?All checkpoints/s', $html);
    }

    public function test_runner_page_renders_and_unknown_bib_404s(): void
    {
        $maria = $this->runner(7, ['first_name' => 'Maria', 'last_name' => 'Santos']);
        $this->pass($maria, 'as1', '2026-11-14 00:30', '2026-11-14 00:40');
        $this->pass($maria, 'as2', '2026-11-14 03:10', '2026-11-14 03:20');

        $this->get('/live/100km/runners/100-007')
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertSee('Next checkpoint')
            ->assertSee('Dalupirip (2nd pass)');

        $this->get('/live/100km/runners/100-999')->assertNotFound();
    }

    // ---------------------------------------------------------------- Live updates

    public function test_race_centre_updates_filter_search_and_page_on_the_server(): void
    {
        foreach (range(1, 30) as $bib) {
            $runner = $this->runner($bib, ['first_name' => "Runner{$bib}", 'last_name' => 'Test']);
            $this->pass($runner, 'as1', '2026-11-14 00:' . str_pad($bib, 2, '0', STR_PAD_LEFT));
        }
        $this->pass(Registration::where('bib_number', 7)->sole(), 'as2', '2026-11-14 03:00');

        $all = $this->getJson('/live/100km/updates')->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertJsonStructure(['regions' => ['stats', 'feed', 'leaders'], 'counts', 'leader', 'matches', 'expected', 'runners']);
        $this->assertSame(31, $all['matches']);
        $this->assertSame(25, substr_count($all['regions']['feed'], '<tr>')); // first page only
        $this->assertSame(31, $all['counts']['']);
        $this->assertSame(1, $all['counts']['as2']);

        $more = $this->getJson('/live/100km/updates?limit=50');
        $this->assertSame(31, substr_count($more['regions']['feed'], '<tr>'));

        $at = $this->getJson('/live/100km/updates?cp=as2');
        $this->assertSame(1, $at['matches']);
        $this->assertStringContainsString('Runner7 Test', $at['regions']['feed']);

        $search = $this->getJson('/live/100km/updates?q=100-012');
        $this->assertSame(1, $search['matches']);
        $this->assertStringContainsString('Runner12 Test', $search['regions']['feed']);
    }

    public function test_updates_need_no_session(): void
    {
        $this->runner(1);

        $this->getJson('/live/100km/updates')->assertOk()->assertCookieMissing(config('session.cookie'));
        $this->getJson('/live/100km/runners/100-001/updates')->assertOk()->assertCookieMissing(config('session.cookie'));
    }

    public function test_runner_updates_return_the_changing_parts(): void
    {
        $maria = $this->runner(7, ['first_name' => 'Maria', 'last_name' => 'Santos']);
        $this->pass($maria, 'as1', '2026-11-14 00:30', '2026-11-14 00:40');

        $this->getJson('/live/100km/runners/100-007/updates')
            ->assertOk()
            ->assertJsonStructure(['regions' => ['status', 'stats', 'timeline', 'next']])
            ->assertJsonPath('regions.status', fn ($html) => str_contains($html, 'On course to Dalupirip'));

        $this->getJson('/live/100km/runners/100-999/updates')->assertNotFound();
    }

    public function test_times_survive_the_real_file_cache(): void
    {
        // The app's file cache won't unserialize objects, so times must be cached as text.
        config(['live.cache_store' => 'file']);
        LiveRace::forget('100km');
        $runner = $this->runner(7);
        $this->pass($runner, 'as1', '2026-11-14 00:30', '2026-11-14 00:40');

        LiveRace::load('100km');                 // fills the cache
        $cached = LiveRace::load('100km');       // read back from it

        $pass = $cached->runners['100-007']['passes'][0];
        $this->assertTrue($pass['in']->equalTo(CarbonImmutable::parse('2026-11-14 00:30', 'Asia/Manila')));
        $this->assertSame('Asia/Manila', $pass['out']->getTimezone()->getName());
        $this->getJson('/live/100km/updates')->assertOk();

        LiveRace::forget('100km');
    }

    public function test_a_recorded_time_shows_up_straight_away_despite_the_cache(): void
    {
        $this->runner(35, ['first_name' => 'Juan', 'last_name' => 'Cruz']);
        $this->assertSame(0, $this->getJson('/live/100km/updates')['matches']); // now cached

        $this->actingAs($this->admin())
            ->postJson('/admin/live/100km/passes', ['bib' => '35', 'checkpoint' => 'as1', 'type' => 'in', 'time' => '00:42']);

        $this->assertSame(1, $this->getJson('/live/100km/updates')['matches']);
    }

    // ---------------------------------------------------------------- Timing console

    public function test_timing_console_records_an_in_time(): void
    {
        $runner = $this->runner(35);
        $this->hundred->update(['gun_start_at' => CarbonImmutable::parse('2026-11-13 23:00', 'Asia/Manila')->utc()]); // stored in UTC, like the app does

        $this->actingAs($admin = $this->admin())
            ->postJson('/admin/live/100km/passes', ['bib' => '35', 'checkpoint' => 'as1', 'type' => 'in', 'time' => '00:42'])
            ->assertOk()
            ->assertJson(['bib' => '100-035', 'type' => 'In', 'at' => '12:42 AM']);

        $pass = CheckpointPass::sole();
        $this->assertSame($runner->id, $pass->registration_id);
        $this->assertSame($admin->id, $pass->recorded_by);
        // 12:42 AM comes after the Friday 11 PM gun, so it's Saturday.
        $this->assertTrue($pass->in_at->equalTo(CarbonImmutable::parse('2026-11-14 00:42', 'Asia/Manila')));
    }

    public function test_without_a_gun_a_typed_time_is_the_nearest_to_now(): void
    {
        $this->runner(35);

        // It's Saturday 10 AM and no gun is set: 9:50 means this morning, not some other day.
        $this->actingAs($this->admin())
            ->postJson('/admin/live/100km/passes', ['bib' => '35', 'checkpoint' => 'as1', 'type' => 'in', 'time' => '09:50'])
            ->assertOk();

        $this->assertTrue(CheckpointPass::sole()->in_at->equalTo(CarbonImmutable::parse('2026-11-14 09:50', 'Asia/Manila')));
    }

    public function test_a_typed_time_lands_on_the_right_night(): void
    {
        $runner = $this->runner(35);
        $this->pass($runner, 'as10', '2026-11-14 22:30', '2026-11-14 22:40');

        // 1:15 AM after a 10:30 PM Saturday arrival is Sunday, not Saturday.
        $this->actingAs($this->admin())
            ->postJson('/admin/live/100km/passes', ['bib' => '100-035', 'checkpoint' => 'as11', 'type' => 'in', 'time' => '01:15'])
            ->assertOk();

        $in = CheckpointPass::where('checkpoint', 'as11')->sole()->in_at;
        $this->assertTrue($in->equalTo(CarbonImmutable::parse('2026-11-15 01:15', 'Asia/Manila')));
    }

    public function test_out_time_goes_on_the_same_pass_and_undo_clears_it(): void
    {
        $this->runner(35);
        $admin = $this->admin();

        $this->actingAs($admin)->postJson('/admin/live/100km/passes', ['bib' => '35', 'checkpoint' => 'as1', 'type' => 'in', 'time' => '00:42']);
        $id = $this->actingAs($admin)
            ->postJson('/admin/live/100km/passes', ['bib' => '35', 'checkpoint' => 'as1', 'type' => 'out', 'time' => '00:50'])
            ->json('id');

        $this->assertNotNull(CheckpointPass::sole()->out_at);

        $this->actingAs($admin)->deleteJson("/admin/live/100km/passes/{$id}?type=out")->assertOk();
        $this->assertNull(CheckpointPass::sole()->out_at);

        // Removing the last time removes the row.
        $this->actingAs($admin)->deleteJson("/admin/live/100km/passes/{$id}?type=in")->assertOk();
        $this->assertSame(0, CheckpointPass::count());
    }

    public function test_unknown_or_wrong_race_bib_is_rejected(): void
    {
        $this->runner(35);

        foreach (['36', '60-035', 'abc'] as $bib) {
            $this->actingAs($this->admin())
                ->postJson('/admin/live/100km/passes', ['bib' => $bib, 'checkpoint' => 'as1', 'type' => 'in', 'time' => '00:42'])
                ->assertUnprocessable()
                ->assertJsonValidationErrors('bib');
        }
        $this->assertSame(0, CheckpointPass::count());
    }

    // ---------------------------------------------------------------- Corrections and DNF

    public function test_corrections_form_saves_times_and_dnf(): void
    {
        $runner = $this->runner(35);
        $this->pass($runner, 'as1', '2026-11-14 00:30', '2026-11-14 00:40');

        $this->actingAs($this->admin())
            ->put('/admin/live/100km/runners/100-035', [
                'passes' => [
                    'as1' => ['in' => '2026-11-14T00:31', 'out' => ''],   // fix In, clear Out
                    'as2' => ['in' => '2026-11-14T03:05', 'out' => '2026-11-14T03:15'],
                ],
                'status'         => 'dnf',
                'dnf_checkpoint' => 'as2',
                'dnf_reason'     => 'Medical',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $as1 = CheckpointPass::where('checkpoint', 'as1')->sole();
        $this->assertTrue($as1->in_at->equalTo(CarbonImmutable::parse('2026-11-14 00:31', 'Asia/Manila')));
        $this->assertNull($as1->out_at);
        $this->assertSame(2, CheckpointPass::count());

        $runner->refresh();
        $this->assertSame(['as2', 'Medical'], [$runner->dnf_checkpoint, $runner->dnf_reason]);
        $this->assertSame('dnf', LiveRace::load('100km')->runners['100-035']['status']);
    }

    public function test_corrections_reject_out_before_in(): void
    {
        $this->runner(35);

        $this->actingAs($this->admin())
            ->put('/admin/live/100km/runners/100-035', [
                'passes' => ['as1' => ['in' => '2026-11-14T00:40', 'out' => '2026-11-14T00:30']],
                'status' => 'racing',
            ])
            ->assertSessionHasErrors('passes.as1.out');

        $this->assertSame(0, CheckpointPass::count());
    }

    public function test_mark_and_undo_dnf_from_the_overview(): void
    {
        $runner = $this->runner(35);
        $this->pass($runner, 'as1', '2026-11-14 00:30', '2026-11-14 00:40');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/live/100km/runners/100-035/dnf')->assertRedirect();
        $this->assertSame('as1', $runner->refresh()->dnf_checkpoint);

        $this->actingAs($admin)->delete('/admin/live/100km/runners/100-035/dnf')->assertRedirect();
        $this->assertNull($runner->refresh()->dnf_checkpoint);
    }

    // ---------------------------------------------------------------- Gun start and reset

    public function test_admin_can_set_the_actual_gun_start(): void
    {
        $this->actingAs($this->admin())
            ->put('/admin/live/100km/gun', ['gun_start' => '2026-11-13T23:12'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $race = LiveRace::load('100km');
        $this->assertSame('11:12 PM Fri', Format::timeWithDay($race->course['gun']));
        // Race time counts from the actual start: Fri 11:12 PM to Sat 10:00 AM.
        $this->assertSame((10 * 60 + 48) * 60, $race->stats()['race_seconds']);
        // Cutoffs stay where the organisers set them.
        $this->assertSame('1:00 AM Sat', Format::timeWithDay($race->checkpoints()[0]['cutoff']));
    }

    public function test_gun_went_off_now_uses_the_server_clock(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-13 23:04:37', 'Asia/Manila'));

        $this->actingAs($this->admin())->put('/admin/live/100km/gun', ['now' => '1'])->assertSessionHasNoErrors();

        $this->assertTrue($this->hundred->refresh()->gun_start_at->equalTo(CarbonImmutable::parse('2026-11-13 23:04', 'Asia/Manila')));
    }

    public function test_the_gun_start_set_in_admin_is_the_source_of_truth(): void
    {
        $this->runner(1);

        // Past the scheduled 11 PM Friday start, but nobody has set the gun: not started.
        $race = LiveRace::load('100km');
        $this->assertFalse($race->started());
        $this->assertSame(0, $race->stats()['race_seconds']);
        $this->assertSame('not_started', $race->runners['100-001']['status']);

        // Any time is accepted, even days away from the schedule (e.g. a test run this week).
        $this->actingAs($this->admin())
            ->put('/admin/live/100km/gun', ['gun_start' => '2026-11-14T08:00'])
            ->assertSessionHasNoErrors();

        $race = LiveRace::load('100km');
        $this->assertTrue($race->started());
        $this->assertSame(2 * 3600, $race->stats()['race_seconds']); // 8 AM to 10 AM Saturday
        $this->assertSame('on_course', $race->runners['100-001']['status']);
    }

    public function test_reset_clears_this_races_times_dnfs_and_gun_only(): void
    {
        $runner = $this->runner(35, ['dnf_checkpoint' => 'as1', 'dnf_reason' => 'Withdrew', 'dnf_at' => now()]);
        $this->pass($runner, 'as1', '2026-11-14 00:30', '2026-11-14 00:40');
        $this->hundred->update(['gun_start_at' => CarbonImmutable::parse('2026-11-13 23:10', 'Asia/Manila')->utc()]);

        // A 60K runner's time must survive a 100K reset.
        $sixty = RaceCategory::factory()->create(['slug' => 'tgc-60', 'bib_start_number' => 60]);
        $other = Registration::factory()->approved()->create(['race_category_id' => $sixty->id, 'bib_number' => 1]);
        $this->pass($other, 'as1', '2026-11-14 04:30');

        $this->actingAs($this->admin())
            ->delete('/admin/live/100km/race')
            ->assertRedirect()
            ->assertSessionHas('success', 'TGC 100 KM was reset: 1 checkpoint time and 1 DNF cleared.');

        $this->assertSame(0, CheckpointPass::where('registration_id', $runner->id)->count());
        $this->assertNull($runner->refresh()->dnf_checkpoint);
        $this->assertNull($this->hundred->refresh()->gun_start_at);
        $this->assertSame(1, CheckpointPass::where('registration_id', $other->id)->count());
        $this->assertNotNull($runner->fresh()); // the registration itself is kept
    }

    public function test_overview_offers_the_kebab_menu_and_reset_confirmation(): void
    {
        $runner = $this->runner(35);
        $this->pass($runner, 'as1', '2026-11-14 00:30');

        $this->actingAs($this->admin())->get('/admin/live/100km')
            ->assertOk()
            ->assertSee('Race clock options')
            ->assertSee('Set gun start…')
            ->assertSee('Reset race…')
            ->assertSee('Deletes 1 recorded checkpoint time');
    }

    // ---------------------------------------------------------------- Course and ETAs

    public function test_course_merges_timed_stations_with_course_config(): void
    {
        $course = Course::find('100km');

        $this->assertSame(
            ['as1', 'as2', 'as6', 'as8', 'as9', 'as10', 'as11', 'finish'],
            array_column($course['checkpoints'], 'code'),
        );
        $this->assertSame('Dalupirip (2nd pass)', $course['checkpoints'][2]['display']);
        $this->assertTrue($course['checkpoints'][0]['crew_access']);
        $this->assertFalse($course['checkpoints'][3]['crew_access']); // Kotkot
        // Kotkot has no cutoff of its own, so it gets one between AS6 and AS9.
        $this->assertGreaterThan($course['checkpoints'][2]['cutoff_minutes'], $course['checkpoints'][3]['cutoff_minutes']);
        $this->assertLessThan($course['checkpoints'][4]['cutoff_minutes'], $course['checkpoints'][3]['cutoff_minutes']);
    }

    public function test_eta_needs_two_checkpoints_and_is_always_a_window(): void
    {
        $course = Course::find('100km');
        $gun = $course['gun'];
        $now = $gun->addHours(4);
        $runner = fn (array $passes) => ['status' => 'on_course', 'passes' => $passes];

        $one = [0 => ['in' => $gun->addHour(), 'out' => $gun->addMinutes(65)]];
        $this->assertNull(Eta::window($runner($one), $course, 1, $now));

        $two = $one + [1 => ['in' => $gun->addHours(3), 'out' => $gun->addMinutes(190)]];
        $eta = Eta::window($runner($two), $course, 2, $now);

        $this->assertNotNull($eta);
        $this->assertTrue($eta['from']->lessThan($eta['to']));
        $this->assertTrue($eta['from']->greaterThanOrEqualTo($now->startOfHour()));
    }

    // ---------------------------------------------------------------- Access

    public function test_admin_pages_need_an_admin(): void
    {
        $this->runner(1);

        $this->get('/admin/live')->assertRedirect('/login');
        $this->postJson('/admin/live/100km/passes', [])->assertUnauthorized();

        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get('/admin/live')->assertForbidden();

        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/live')->assertOk()->assertSee('Live Tracking');
        $this->actingAs($admin)->get('/admin/live/60km/timing')->assertOk()->assertSee('Timing console');
        $this->actingAs($admin)->get('/admin/live/100km/runners/100-001')->assertOk()->assertSee('Checkpoint times');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

/** Public route map page and the GPX → JSON converter behind it. */
class CourseRouteTest extends TestCase
{
    private string $slug = 'testkm';

    protected function tearDown(): void
    {
        File::delete(public_path("courses/{$this->slug}.json"));
        parent::tearDown();
    }

    private function gpx(): string
    {
        // Three points heading north, roughly 111 m apart, climbing then dropping.
        $path = tempnam(sys_get_temp_dir(), 'gpx');
        file_put_contents($path, <<<'GPX'
            <?xml version="1.0" encoding="UTF-8"?>
            <gpx version="1.1" xmlns="http://www.topografix.com/GPX/1/1">
              <metadata><name>Test Course</name></metadata>
              <trk><trkseg>
                <trkpt lat="16.000" lon="120.000"><ele>1000</ele></trkpt>
                <trkpt lat="16.001" lon="120.000"><ele>1050</ele></trkpt>
                <trkpt lat="16.002" lon="120.000"><ele>1020</ele></trkpt>
              </trkseg></trk>
            </gpx>
            GPX);

        return $path;
    }

    public function test_build_command_writes_the_course_json(): void
    {
        $this->artisan('course:build', ['gpx' => $this->gpx(), 'slug' => $this->slug, '--step' => 1])
            ->assertSuccessful();

        $course = json_decode(File::get(public_path("courses/{$this->slug}.json")), true);

        $this->assertSame('Test Course', $course['name']);
        $this->assertEqualsWithDelta(0.22, $course['distance_km'], 0.01);
        $this->assertSame(50, $course['gain_m']);
        $this->assertSame(30, $course['loss_m']);
        $this->assertSame(1000, $course['min_ele_m']);
        $this->assertSame(1050, $course['max_ele_m']);
        $this->assertCount(3, $course['points']);
        // [lng, lat, ele, km]
        $this->assertSame([120, 16, 1000, 0], $course['points'][0]);
    }

    public function test_build_command_fails_for_a_missing_file(): void
    {
        $this->artisan('course:build', ['gpx' => 'nope.gpx', 'slug' => $this->slug])->assertFailed();
    }

    public function test_100km_route_page_shows_the_course_and_its_aid_stations(): void
    {
        $this->get(route('race-category.route', '100km'))
            ->assertOk()
            ->assertSee('TGC 100 KM')
            ->assertSeeInOrder(['Est. Distance', '104 KM', '6,200M D+', 'Cutoff Time', '32 hrs', '14 hrs at 55 km',
                'Race Date', 'Nov 13, 2026', 'Gunstart', '11 PM', 'Friday · UTC+8'])
            ->assertSee('"intermediate_cutoff":true', false)
            ->assertDontSee('Highest Point')
            ->assertSee('courses\/100km.json?v=', false)
            ->assertSee('"code":"AS6"', false)          // stations are handed to the map script
            ->assertSee('Finish cutoff')
            ->assertDontSee('will be announced soon');
    }

    public function test_60km_route_page_shows_its_own_course_in_red(): void
    {
        $this->get(route('race-category.route', '60km'))
            ->assertOk()
            ->assertSee('TGC 60 KM')
            ->assertSee('courses\/60km.json?v=', false)
            ->assertSee('"code":"WS2"', false)
            ->assertSee('--accent: #ef4444', false)
            ->assertSee('9:00 PM Sat')
            ->assertSeeInOrder(['Est. Distance', '63 KM', '3,500M D+', '18 hrs', '8 hrs at 31 km', '3 AM', 'Saturday · UTC+8'])
            ->assertSee('"code":"AS4","name":"Santa Fe, Ampucao","km":30.81,"lat":16.286647,"lng":120.64383,"cutoff":"11:00 AM Sat","intermediate_cutoff":true', false);
    }

    public function test_21km_route_page_shows_its_own_course_in_green(): void
    {
        $this->get(route('race-category.route', '21km'))
            ->assertOk()
            ->assertSee('TGC 21 KM')
            ->assertSee('courses\/21km.json?v=', false)
            ->assertSee('"code":"WS1"', false)
            ->assertSee('--accent: #16a34a', false)
            ->assertSee('12:30 PM Sun')
            ->assertSeeInOrder(['Est. Distance', '22 KM', '1,200M D+', '8 hrs', '6.5 hrs at 16 km', '4:30 AM', 'Sunday · UTC+8'])
            ->assertSee('"cutoff":"11:00 AM Sun","intermediate_cutoff":true', false);

        $this->get(route('race-category.21km'))
            ->assertOk()
            ->assertSee(route('race-category.route', '21km'));
    }

    public function test_10km_route_page_shows_its_own_course_in_cyan(): void
    {
        $this->get(route('race-category.route', '10km'))
            ->assertOk()
            ->assertSee('TGC 10 KM')
            ->assertSee('courses\/10km.json?v=', false)
            ->assertSee('"code":"WS3"', false)
            ->assertSee('--accent: #0891b2', false)
            ->assertSee('9:30 AM Sun')
            ->assertSeeInOrder(['Est. Distance', '10 KM', '400M D+', '3 hrs', '6:30 AM', 'Sunday · UTC+8']);

        $this->get(route('race-category.10km'))
            ->assertOk()
            ->assertSee(route('race-category.route', '10km'));
    }

    public function test_100km_route_page_keeps_orange(): void
    {
        $this->get(route('race-category.route', '100km'))
            ->assertSee('--accent: #f97316', false)
            ->assertSee('--accent-rgb: 249 115 22', false);
    }

    public function test_60km_page_links_to_the_route_map(): void
    {
        $this->get(route('race-category.60km'))
            ->assertOk()
            ->assertSee(route('race-category.route', '60km'));
    }

    public function test_every_route_page_offers_the_gpx_download(): void
    {
        foreach (['100km', '60km', '21km', '10km'] as $slug) {
            $this->get(route('race-category.route', $slug))
                ->assertOk()
                ->assertSee('Download the GPX')
                ->assertSee('/images/gpx-qr-code.png', false)
                ->assertSee('https://drive.google.com/drive/folders/1K4x-vAubwb-a0-hBSswoGsIe9aeUQxYN?usp=sharing', false);
        }

        $this->assertFileExists(public_path('images/gpx-qr-code.png'));
    }

    public function test_route_page_says_when_aid_stations_are_not_announced_yet(): void
    {
        config(['courses.100km.aid_stations' => []]);

        $this->get(route('race-category.route', '100km'))
            ->assertOk()
            ->assertSee('Aid station locations will be announced soon.');
    }

    public function test_100km_page_links_to_the_route_map(): void
    {
        $this->get(route('race-category.100km'))
            ->assertOk()
            ->assertSee(route('race-category.route', '100km'));
    }

    public function test_categories_without_a_course_are_not_found(): void
    {
        config(['courses.60km' => null]);

        $this->get(route('race-category.route', '60km'))->assertNotFound();
    }

    public function test_unknown_slugs_are_not_routed(): void
    {
        $this->get('/race-category/5km/route')->assertNotFound();
    }
}

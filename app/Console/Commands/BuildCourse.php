<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Turns a raw GPX track into the compact JSON the public route map loads.
 * Raw exports are ~75k points (10 MB); the page only needs a point every few
 * metres, so the track is resampled by distance and written to public/courses.
 */
class BuildCourse extends Command
{
    protected $signature = 'course:build
        {gpx : Path to the GPX file}
        {slug : Course slug, e.g. 100km (written to public/courses/{slug}.json)}
        {--step=20 : Metres between kept points}';

    protected $description = 'Convert a GPX track into the route map JSON for a race category';

    public function handle(): int
    {
        $path = $this->argument('gpx');

        if (! is_file($path)) {
            $this->error("GPX file not found: {$path}");

            return self::FAILURE;
        }

        $gpx = simplexml_load_file($path);
        $points = [];

        foreach ($gpx->trk as $trk) {
            foreach ($trk->trkseg as $seg) {
                foreach ($seg->trkpt as $pt) {
                    $points[] = [(float) $pt['lat'], (float) $pt['lon'], (float) $pt->ele];
                }
            }
        }

        if (count($points) < 2) {
            $this->error('The GPX file has no track points.');

            return self::FAILURE;
        }

        $step = max(1, (int) $this->option('step'));
        $kept = [];
        $total = 0.0;
        $sinceKept = 0.0;

        foreach ($points as $i => $pt) {
            if ($i > 0) {
                $d = $this->metres($points[$i - 1], $pt);
                $total += $d;
                $sinceKept += $d;
            }

            if ($i === 0 || $sinceKept >= $step || $i === count($points) - 1) {
                // [lng, lat, elevation m, distance km]: lng first to match GeoJSON
                $kept[] = [round($pt[1], 6), round($pt[0], 6), round($pt[2], 1), round($total / 1000, 3)];
                $sinceKept = 0.0;
            }
        }

        [$gain, $loss] = $this->climb(array_column($kept, 2));
        $elevations = array_column($kept, 2);

        $course = [
            'name'        => (string) ($gpx->metadata->name ?? $gpx->trk->name ?? $this->argument('slug')),
            'distance_km' => round($total / 1000, 2),
            'gain_m'      => (int) round($gain),
            'loss_m'      => (int) round($loss),
            'min_ele_m'   => (int) round(min($elevations)),
            'max_ele_m'   => (int) round(max($elevations)),
            'points'      => $kept,
        ];

        $dir = public_path('courses');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $out = "{$dir}/{$this->argument('slug')}.json";
        file_put_contents($out, json_encode($course, JSON_UNESCAPED_SLASHES));

        $this->info(sprintf(
            '%s: %s km, +%d m / -%d m, %d → %d points (%s KB) → %s',
            $course['name'], $course['distance_km'], $course['gain_m'], $course['loss_m'],
            count($points), count($kept), round(filesize($out) / 1024), $out
        ));

        return self::SUCCESS;
    }

    private function metres(array $a, array $b): float
    {
        $dLat = deg2rad($b[0] - $a[0]);
        $dLng = deg2rad($b[1] - $a[1]);
        $h = sin($dLat / 2) ** 2 + cos(deg2rad($a[0])) * cos(deg2rad($b[0])) * sin($dLng / 2) ** 2;

        return 2 * 6371000 * asin(sqrt($h));
    }

    // Elevation gain/loss with a 5 m threshold so GPS noise isn't counted as climbing.
    private function climb(array $elevations): array
    {
        $gain = $loss = 0.0;
        $ref = $elevations[0];

        foreach ($elevations as $e) {
            if ($e - $ref >= 5) {
                $gain += $e - $ref;
                $ref = $e;
            } elseif ($ref - $e >= 5) {
                $loss += $ref - $e;
                $ref = $e;
            }
        }

        return [$gain, $loss];
    }
}

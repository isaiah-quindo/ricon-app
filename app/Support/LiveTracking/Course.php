<?php

namespace App\Support\LiveTracking;

use Carbon\CarbonImmutable;

/**
 * The timed checkpoints of a tracked race, built from config/live.php (which stations
 * are timed) and config/courses.php (km, cutoff, crew access). Finish is always last.
 */
class Course
{
    /** @return string[] Slugs that have live tracking, e.g. ['100km', '60km']. */
    public static function slugs(): array
    {
        return array_keys(config('live.races'));
    }

    /**
     * $gun is the gun start set in admin, the one source of truth for when the race began.
     * Until it's set the race hasn't started; the scheduled time from config/courses.php
     * is only shown as a hint and used as a placeholder in the maths.
     */
    public static function find(string $slug, ?CarbonImmutable $gun = null): ?array
    {
        $live = config("live.races.{$slug}");
        $course = config("courses.{$slug}");

        if (! $live || ! $course) {
            return null;
        }

        $tz = config('live.timezone');
        // "Nov 13, 2026" + "11 PM" from the course config, read as race-day local time.
        $scheduled = CarbonImmutable::parse("{$course['race_date']} {$course['gunstart']}", $tz);
        $gunSet = $gun !== null;
        $gun = $gun?->setTimezone($tz) ?? $scheduled;

        $stations = collect($course['aid_stations'])->keyBy('code');
        $seen = [];
        $checkpoints = [];

        foreach ($live['checkpoints'] as $code => $name) {
            $station = $stations[$code];
            // Count visits to the same place so the second one reads "Dalupirip (2nd pass)".
            $pass = $seen[$name] = ($seen[$name] ?? 0) + 1;

            $checkpoints[] = [
                'code'        => strtolower($code),
                'label'       => $code,
                'name'        => $name,
                'display'     => $pass > 1 ? "{$name} (2nd pass)" : $name,
                'km'          => (float) $station['km'],
                'cutoff'      => self::cutoff($station['cutoff'] ?? null, $scheduled),
                'crew_access' => in_array('Crew access', $station['services'] ?? [], true),
                'is_finish'   => false,
            ];
        }

        $checkpoints[] = [
            'code'        => 'finish',
            'label'       => 'FIN',
            'name'        => 'Finish',
            'display'     => 'Finish',
            'km'          => (float) $live['finish_km'],
            'cutoff'      => self::cutoff($course['finish_cutoff'] ?? null, $scheduled),
            'crew_access' => true,
            'is_finish'   => true,
        ];

        return [
            'slug'          => $slug,
            'title'         => $course['title'],
            'gun'           => $gun,
            'gun_set'       => $gunSet,
            'scheduled_gun' => $scheduled,
            'finish_km'     => (float) $live['finish_km'],
            'race_category' => $live['race_category'],
            'checkpoints'   => self::withCutoffMinutes($checkpoints, $gun),
        ];
    }

    /**
     * Adds `cutoff_minutes`: minutes from the gun to each checkpoint's cutoff. Organisers
     * give hard sections more time, so this doubles as a terrain profile for ETAs.
     * Checkpoints without a cutoff (e.g. Kotkot) get one interpolated by distance.
     */
    private static function withCutoffMinutes(array $checkpoints, CarbonImmutable $gun): array
    {
        $known = [[0.0, 0.0]];
        foreach ($checkpoints as $cp) {
            if ($cp['cutoff']) {
                $known[] = [$cp['km'], $gun->diffInMinutes($cp['cutoff'])];
            }
        }

        foreach ($checkpoints as &$cp) {
            foreach ($known as $k => [$km, $minutes]) {
                if ($km >= $cp['km']) {
                    [$prevKm, $prevMinutes] = $known[$k - 1];
                    $cp['cutoff_minutes'] = $prevMinutes + ($minutes - $prevMinutes) * ($cp['km'] - $prevKm) / ($km - $prevKm);
                    break;
                }
            }
            $cp['cutoff_minutes'] ??= end($known)[1];
        }
        unset($cp); // Drop the reference so later code can't edit the last checkpoint by accident.

        return $checkpoints;
    }

    /**
     * Turns a cutoff like "1:00 PM Sat" into a date. The day name means the first
     * such day on or after the gun date, since a race never lasts a week.
     */
    private static function cutoff(?string $text, CarbonImmutable $gun): ?CarbonImmutable
    {
        if (! $text || ! preg_match('/^(.+?)\s+(Mon|Tue|Wed|Thu|Fri|Sat|Sun)$/', $text, $m)) {
            return null;
        }

        $day = $gun->startOfDay();
        while ($day->format('D') !== $m[2]) {
            $day = $day->addDay();
        }

        return CarbonImmutable::parse($day->toDateString() . ' ' . $m[1], $gun->getTimezone());
    }
}

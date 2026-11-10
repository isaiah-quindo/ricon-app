<?php

namespace App\Support\LiveTracking;

use App\Models\RaceCategory;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * The runners of a tracked race, read from the database: approved registrations with a
 * bib in the race's category, with their checkpoint times and DNF, in the shape LiveRace uses.
 */
class RaceEntries
{
    public static function category(array $course): ?RaceCategory
    {
        return RaceCategory::where('slug', $course['race_category'])->first();
    }

    /**
     * @return array<string, array> Runners keyed by bib label ("100-007"). `passes` is keyed by
     *                              checkpoint index, in course order.
     */
    public static function runners(array $course, RaceCategory $category): array
    {
        $tz = config('live.timezone');
        // "as6" => 2, so stored checkpoint codes map onto the course order.
        $index = array_flip(array_column($course['checkpoints'], 'code'));

        $registrations = $category->registrations()
            ->where('status', 'approved')
            ->whereNotNull('bib_number')
            ->with('checkpointPasses')
            ->get();

        $runners = [];
        foreach ($registrations as $registration) {
            $registration->setRelation('raceCategory', $category); // for formatted_bib, without a query each

            $passes = [];
            foreach ($registration->checkpointPasses as $pass) {
                // A runner whose In was missed but Out was recorded still counts as arriving then.
                $in = $pass->in_at ?? $pass->out_at;
                if (! isset($index[$pass->checkpoint]) || ! $in) {
                    continue;
                }
                $passes[$index[$pass->checkpoint]] = [
                    'id'  => $pass->id,
                    'in'  => $in->setTimezone($tz),
                    'out' => $pass->out_at?->setTimezone($tz),
                ];
            }
            ksort($passes);

            $dnfIndex = $index[$registration->dnf_checkpoint] ?? null;

            $runners[$registration->formatted_bib] = [
                'bib'             => $registration->formatted_bib,
                'registration_id' => $registration->id,
                'name'            => trim("{$registration->first_name} {$registration->last_name}"),
                'sex'             => $registration->sex === 'female' ? 'F' : 'M',
                'age_group'       => self::ageGroup($registration->birthdate, $course['gun']),
                'passes'          => $passes,
                'dnf'             => $dnfIndex === null ? null : [
                    'index'  => $dnfIndex,
                    'at'     => ($registration->dnf_at ?? CarbonImmutable::now())->setTimezone($tz),
                    'reason' => $registration->dnf_reason ?? 'Withdrew',
                ],
            ];
        }

        ksort($runners);

        return $runners;
    }

    /** Age on race day, in the same groups as the admin registrations filter. */
    private static function ageGroup(?CarbonInterface $birthdate, CarbonImmutable $raceDay): string
    {
        if (! $birthdate) {
            return 'Open';
        }

        $age = (int) $birthdate->diffInYears($raceDay);

        return match (true) {
            $age < 20 => 'Under 20',
            $age < 30 => '20-29',
            $age < 40 => '30-39',
            $age < 50 => '40-49',
            default   => '50+',
        };
    }
}

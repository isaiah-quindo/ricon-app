<?php

namespace App\Support\LiveTracking;

use Carbon\CarbonImmutable;

/**
 * Estimated arrival at a checkpoint, always as a window and never a single time.
 *
 * It projects how the runner is doing against the cutoffs onto the sections ahead, plus
 * a little for fatigue. The window starts wide and narrows with every checkpoint the
 * runner passes, because each one tells us more about how they're moving.
 */
class Eta
{
    /** Below this many checkpoints we don't know enough about a runner to guess. */
    public const MIN_CHECKPOINTS = 2;

    /** @return array{from: CarbonImmutable, to: CarbonImmutable, overdue: bool}|null */
    public static function window(array $runner, array $course, int $target, CarbonImmutable $now): ?array
    {
        $passes = $runner['passes'];
        if (count($passes) < self::MIN_CHECKPOINTS || ! in_array($runner['status'], ['on_course', 'at_station'], true)) {
            return null;
        }

        $last = array_key_last($passes);
        if ($target <= $last || ! isset($course['checkpoints'][$target])) {
            return null;
        }

        // How the runner is doing against the cutoffs so far (0.6 = using 60% of the allowed
        // time). Cutoffs give steep sections more time, so this beats plain km/h on a mountain course.
        $budget = fn ($c) => $course['checkpoints'][$c]['cutoff_minutes'];
        $ratio = $course['gun']->diffInMinutes($passes[$last]['in']) / $budget($last);

        // Seconds from their last arrival to checkpoint $c. 5% extra for fatigue; measuring
        // from arrival means aid station time is included.
        $leg = fn ($c) => ($budget($c) - $budget($last)) * $ratio * 1.05 * 60;

        // ±25% of the leg after 2 checkpoints, 4 points tighter for each one after that, never under ±8%.
        $spread = max(0.08, 0.25 - 0.04 * (count($passes) - self::MIN_CHECKPOINTS));
        $half = (int) round($leg($target) * $spread);

        // Running late for the next checkpoint pushes everything after it back by the same amount.
        $nextExpected = $passes[$last]['in']->addSeconds((int) round($leg($last + 1)));
        $late = max(0, (int) $nextExpected->diffInSeconds($now, false));
        // Only the next checkpoint can be "overdue"; later ones have simply moved back.
        $overdue = $target === $last + 1 && $late > $leg($last + 1) * $spread;

        $expected = $passes[$last]['in']->addSeconds((int) round($leg($target)) + $late);
        $from = self::roundTo5($expected->subSeconds($half), 'floor');
        $to = self::roundTo5($expected->addSeconds($half), 'ceil');

        // A window that has already started can't begin in the past.
        if ($from->lessThan($now)) {
            $from = self::roundTo5($now, 'floor');
            $to = $to->max($from->addMinutes(15));
        }

        return ['from' => $from, 'to' => $to, 'overdue' => $overdue];
    }

    private static function roundTo5(CarbonImmutable $time, string $direction): CarbonImmutable
    {
        $minutes = $direction === 'floor'
            ? floor($time->minute / 5) * 5
            : ceil(($time->minute + ($time->second > 0 ? 1 : 0)) / 5) * 5;

        return $time->setTime($time->hour, 0)->addMinutes((int) $minutes);
    }
}

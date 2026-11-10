<?php

namespace App\Support\LiveTracking;

use Carbon\CarbonImmutable;

/** How times and durations read on the live pages, kept in one place so every view agrees. */
class Format
{
    /** "2:27 PM" */
    public static function time(CarbonImmutable $time): string
    {
        return $time->format('g:i A');
    }

    /** "2:27 PM Sat", for times that may fall on another day, like cutoffs. */
    public static function timeWithDay(CarbonImmutable $time): string
    {
        return $time->format('g:i A D');
    }

    /** "15:30:00" for the race clock, "2:14" (h:mm) for splits. */
    public static function duration(int $seconds, bool $withSeconds = false): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);

        return $withSeconds
            ? sprintf('%d:%02d:%02d', $h, $m, $seconds % 60)
            : sprintf('%d:%02d', $h, $m);
    }

    /** "Just now", "12 min ago", "2 h 5 min ago" */
    public static function ago(CarbonImmutable $time, CarbonImmutable $now): string
    {
        $minutes = (int) floor($time->diffInMinutes($now));

        return match (true) {
            $minutes < 1  => 'Just now',
            $minutes < 60 => "{$minutes} min ago",
            default       => intdiv($minutes, 60) . ' h ' . ($minutes % 60) . ' min ago',
        };
    }

    /** "3:40–4:25 PM", or "11:50 PM–12:30 AM" when the window crosses noon or midnight. */
    public static function window(array $eta): string
    {
        return $eta['from']->format('A') === $eta['to']->format('A')
            ? $eta['from']->format('g:i') . '–' . self::time($eta['to'])
            : self::time($eta['from']) . '–' . self::time($eta['to']);
    }

    /** "1st", "2nd", "23rd" */
    public static function ordinal(int $n): string
    {
        $suffix = in_array($n % 100, [11, 12, 13], true) ? 'th' : (['th', 'st', 'nd', 'rd'][$n % 10] ?? 'th');

        return $n . $suffix;
    }

    /** "53.7 km" */
    public static function km(float $km): string
    {
        return rtrim(rtrim(number_format($km, 1), '0'), '.') . ' km';
    }
}

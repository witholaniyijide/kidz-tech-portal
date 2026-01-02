<?php

use Carbon\Carbon;

/**
 * Calculate the week number for a given date string.
 * Must match the Assessment Web App calculation exactly.
 */
function weekNumber(string $dateStr): int
{
    $d = Carbon::parse($dateStr)->startOfDay();
    $year = $d->year;
    $jan1 = Carbon::create($year, 1, 1)->startOfDay();
    $jan1Day = $jan1->dayOfWeek;

    if ($jan1Day === 1) {
        $firstFullWeekMonday = $jan1->copy();
    } elseif ($jan1Day === 0) {
        $firstFullWeekMonday = $jan1->copy()->addDay();
    } else {
        $daysUntilNextMonday = 8 - $jan1Day;
        $firstFullWeekMonday = $jan1->copy()->addDays($daysUntilNextMonday);
    }

    if ($d < $firstFullWeekMonday) {
        return 1;
    }

    $daysSinceFirstFullWeek = $d->diffInDays($firstFullWeekMonday);
    return (int) floor($daysSinceFirstFullWeek / 7) + 2;
}

/**
 * Get the date range for a given week number and year.
 */
function getWeekDateRange(int $weekNum, ?int $year = null): array
{
    $year = $year ?? now()->year;
    $jan1 = Carbon::create($year, 1, 1)->startOfDay();
    $jan1Day = $jan1->dayOfWeek;

    if ($weekNum === 1) {
        $weekStart = $jan1->copy();
        if ($jan1Day === 1) {
            $weekEnd = $jan1->copy()->addDays(6);
        } elseif ($jan1Day === 0) {
            $weekEnd = $jan1->copy();
        } else {
            $daysUntilSunday = 7 - $jan1Day;
            $weekEnd = $jan1->copy()->addDays($daysUntilSunday);
        }
        return ['start' => $weekStart->toDateString(), 'end' => $weekEnd->toDateString()];
    }

    if ($jan1Day === 1) {
        $firstFullWeekMonday = $jan1->copy();
    } elseif ($jan1Day === 0) {
        $firstFullWeekMonday = $jan1->copy()->addDay();
    } else {
        $daysUntilNextMonday = 8 - $jan1Day;
        $firstFullWeekMonday = $jan1->copy()->addDays($daysUntilNextMonday);
    }

    $weeksAfterFirst = $weekNum - 2;
    $weekStart = $firstFullWeekMonday->copy()->addDays($weeksAfterFirst * 7);
    $weekEnd = $weekStart->copy()->addDays(6);

    $dec31 = Carbon::create($year, 12, 31);
    if ($weekEnd > $dec31) {
        $weekEnd = $dec31;
    }

    return ['start' => $weekStart->toDateString(), 'end' => $weekEnd->toDateString()];
}

/**
 * Format week range for display.
 */
function formatWeekRange(int $weekNum, ?int $year = null): string
{
    $range = getWeekDateRange($weekNum, $year);
    $start = Carbon::parse($range['start']);
    $end = Carbon::parse($range['end']);

    return $start->format('M j') . ' - ' . $end->format('M j, Y');
}

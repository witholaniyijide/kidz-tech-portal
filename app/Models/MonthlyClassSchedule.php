<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MonthlyClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'student_id',
        'year',
        'month',
        'total_classes',
        'completed_classes',
        'class_days',
        'notes',
    ];

    protected $casts = [
        'class_days' => 'array',
    ];

    /**
     * Get the tutor for this schedule.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Get the student for this schedule.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the month name.
     */
    public function getMonthNameAttribute()
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }

    /**
     * Get remaining classes.
     */
    public function getRemainingClassesAttribute()
    {
        return max(0, $this->total_classes - $this->completed_classes);
    }

    /**
     * Get the completion percentage.
     */
    public function getCompletionPercentageAttribute()
    {
        if ($this->total_classes == 0) {
            return 0;
        }
        return (int) (($this->completed_classes / $this->total_classes) * 100);
    }

    /**
     * Get current class count as fraction (e.g., "3/8")
     */
    public function getClassCountFractionAttribute()
    {
        return $this->completed_classes . '/' . $this->total_classes;
    }

    /**
     * Calculate total classes for a month based on class days.
     *
     * @param int $year
     * @param int $month
     * @param array $classDays Array of day names e.g., ['Monday', 'Wednesday']
     * @return int
     */
    public static function calculateTotalClasses($year, $month, array $classDays)
    {
        if (empty($classDays)) {
            return 0;
        }

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        $count = 0;

        // Map day names to Carbon day constants
        $dayMap = [
            'Sunday' => Carbon::SUNDAY,
            'Monday' => Carbon::MONDAY,
            'Tuesday' => Carbon::TUESDAY,
            'Wednesday' => Carbon::WEDNESDAY,
            'Thursday' => Carbon::THURSDAY,
            'Friday' => Carbon::FRIDAY,
            'Saturday' => Carbon::SATURDAY,
        ];

        $current = $startOfMonth->copy();

        while ($current <= $endOfMonth) {
            $currentDayName = $current->format('l');
            if (in_array($currentDayName, $classDays)) {
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    /**
     * Increment completed classes count.
     */
    public function incrementCompletedClasses()
    {
        $this->completed_classes = min($this->completed_classes + 1, $this->total_classes);
        $this->save();
    }

    /**
     * Get or create monthly schedule for a student.
     *
     * @param int $tutorId
     * @param int $studentId
     * @param int|null $year
     * @param int|null $month
     * @return static|null
     */
    public static function getOrCreateForStudent($tutorId, $studentId, $year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return static::firstOrCreate(
            [
                'student_id' => $studentId,
                'year' => $year,
                'month' => $month,
            ],
            [
                'tutor_id' => $tutorId,
                'total_classes' => 0,
                'completed_classes' => 0,
                'class_days' => [],
            ]
        );
    }

    /**
     * Scope to get schedules for a specific month.
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    /**
     * Scope to get schedules for a specific tutor.
     */
    public function scopeForTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }
}

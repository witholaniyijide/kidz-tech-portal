<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'class_date',
        'class_time',
        'duration_minutes',
        'topic',
        'courses_covered',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'is_stand_in',
        'stand_in_reason',
        'is_late',
        'is_late_submission',
        'is_rescheduled',
        'original_scheduled_time',
        'reschedule_reason',
        'reschedule_notes',
    ];

    protected $casts = [
        'class_date' => 'date',
        'class_time' => 'datetime',
        'approved_at' => 'datetime',
        'is_stand_in' => 'boolean',
        'is_late' => 'boolean',
        'is_late_submission' => 'boolean',
        'is_rescheduled' => 'boolean',
        'courses_covered' => 'array',
    ];

    /**
     * Get the class end time.
     */
    public function getEndTimeAttribute()
    {
        if (!$this->class_time || !$this->duration_minutes) {
            return null;
        }

        return $this->class_time->copy()->addMinutes($this->duration_minutes);
    }

    /**
     * Get formatted class time range.
     */
    public function getClassTimeRangeAttribute()
    {
        if (!$this->class_time) {
            return null;
        }

        $start = $this->class_time->format('g:i A');
        $end = $this->end_time ? $this->end_time->format('g:i A') : null;

        return $end ? "{$start} - {$end}" : $start;
    }

    /**
     * Get courses covered as comma-separated string.
     */
    public function getCoursesCoveredTextAttribute()
    {
        if (empty($this->courses_covered)) {
            return null;
        }

        return implode(', ', $this->courses_covered);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope to get stand-in attendance records
     */
    public function scopeStandIn($query)
    {
        return $query->where('is_stand_in', true);
    }

    /**
     * Scope to get late submissions
     */
    public function scopeLate($query)
    {
        return $query->where('is_late', true);
    }
}

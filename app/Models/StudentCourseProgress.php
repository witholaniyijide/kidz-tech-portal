<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourseProgress extends Model
{
    use HasFactory;

    protected $table = 'student_course_progress';

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
        'source',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the student for this progress record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course for this progress record.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Mark a course as completed for a student.
     */
    public static function markCompleted(int $studentId, int $courseId, string $source = 'manual'): self
    {
        return static::updateOrCreate(
            [
                'student_id' => $studentId,
                'course_id' => $courseId,
            ],
            [
                'status' => 'completed',
                'source' => $source,
                'completed_at' => now(),
            ]
        );
    }

    /**
     * Remove a completed course for a student.
     */
    public static function removeCompletion(int $studentId, int $courseId): bool
    {
        return static::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->delete() > 0;
    }

    /**
     * Check if a student has completed a specific course.
     */
    public static function hasCompleted(int $studentId, int $courseId): bool
    {
        return static::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();
    }
}

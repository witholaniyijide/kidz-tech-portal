<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'month',
        'progress_summary',
        'strengths',
        'weaknesses',
        'next_steps',
        'attendance_score',
        'performance_rating',
        'status',
        'manager_comment',
        'director_comment',
        'submitted_at',
        'approved_by_manager_at',
        'approved_by_director_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_by_manager_at' => 'datetime',
        'approved_by_director_at' => 'datetime',
        'attendance_score' => 'integer',
    ];

    /**
     * Get the tutor that owns the report.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Get the student that this report is about.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get all comments for this report.
     */
    public function comments()
    {
        return $this->hasMany(TutorReportComment::class, 'report_id');
    }

    /**
     * Scope to get only draft reports.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get only submitted reports.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope to get reports for a specific tutor.
     */
    public function scopeForTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }

    /**
     * Scope to get reports approved by manager.
     */
    public function scopeApprovedByManager($query)
    {
        return $query->where('status', 'approved-by-manager');
    }

    /**
     * Scope to get reports approved by director.
     */
    public function scopeApprovedByDirector($query)
    {
        return $query->where('status', 'approved-by-director');
    }

    /**
     * Scope to get reports pending manager review.
     */
    public function scopePendingManagerReview($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Check if report is submitted.
     */
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if report is approved by manager.
     */
    public function isManagerApproved()
    {
        return $this->status === 'approved-by-manager';
    }

    /**
     * Check if report is approved by director.
     */
    public function isDirectorApproved()
    {
        return $this->status === 'approved-by-director';
    }

    /**
     * Check if report is in draft status.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }
}

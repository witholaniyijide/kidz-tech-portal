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
        'director_id',
        'created_by',
        'title',
        'month',
        'year',
        'period_from',
        'period_to',
        
        // Courses (JSON array)
        'courses',
        
        // Skills (JSON arrays)
        'skills_mastered',
        'new_skills',
        
        // Projects (JSON array of objects: [{title, link}])
        'projects',
        
        // Content sections
        'content',
        'summary',
        'progress_summary',
        'strengths',
        'weaknesses',
        'areas_for_improvement',
        'goals_next_month',
        'assignments',
        'comments_observation',
        'next_steps',
        
        // Ratings
        'attendance_score',
        'performance_rating',
        'rating',
        
        // Status workflow
        'status',
        'manager_comment',
        'director_comment',
        'director_signature',
        'submitted_at',
        'approved_by_manager_at',
        'approved_by_director_at',
        
        // Import metadata
        'imported_from_artifact',
        'artifact_export_date',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_by_manager_at' => 'datetime',
        'approved_by_director_at' => 'datetime',
        'attendance_score' => 'integer',
        'courses' => 'array',
        'skills_mastered' => 'array',
        'new_skills' => 'array',
        'projects' => 'array',
        'imported_from_artifact' => 'boolean',
        'artifact_export_date' => 'datetime',
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
     * Get the user who created this report.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the director that approved this report.
     */
    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
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
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get reports approved by director.
     */
    public function scopeApprovedByDirector($query)
    {
        return $query->where('status', 'director_approved');
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
        return in_array($this->status, ['approved', 'director_approved']);
    }

    /**
     * Check if report is approved by director.
     */
    public function isDirectorApproved()
    {
        return $this->status === 'director_approved';
    }

    /**
     * Check if report is in draft status.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if report was returned for revision.
     */
    public function isReturned()
    {
        return $this->status === 'returned';
    }

    /**
     * Check if report can be edited.
     */
    public function canEdit()
    {
        return in_array($this->status, ['draft', 'returned']);
    }

    /**
     * Get formatted month/year.
     */
    public function getFormattedMonthAttribute()
    {
        if ($this->month && $this->year) {
            return $this->month . ' ' . $this->year;
        }
        return $this->month;
    }

    /**
     * Get skills mastered count.
     */
    public function getSkillsMasteredCountAttribute()
    {
        return is_array($this->skills_mastered) ? count($this->skills_mastered) : 0;
    }

    /**
     * Get projects count.
     */
    public function getProjectsCountAttribute()
    {
        if (!is_array($this->projects)) return 0;
        return count(array_filter($this->projects, fn($p) => !empty($p['title'])));
    }

    /**
     * Get all audit logs for this report.
     */
    public function audits()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}

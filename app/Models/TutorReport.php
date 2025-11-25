<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'student_id',
        'title',
        'month',
        'period_from',
        'period_to',
        'content',
        'summary',
        'rating',
        'status',
        'submitted_at',
        'created_by',
    ];

    protected $casts = [
        'period_from' => 'date',
        'period_to' => 'date',
        'submitted_at' => 'datetime',
        'rating' => 'integer',
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
     * Get the user who created the report.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
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
     * Scope to get reports pending manager review.
     */
    public function scopePendingManagerReview($query)
    {
        return $query->where('status', 'manager_review');
    }

    /**
     * Scope to get director-approved reports.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'director_approved');
    }
}

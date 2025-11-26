<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'manager_id',
        'director_id',
        'assessment_month',
        'strengths',
        'weaknesses',
        'recommendations',
        'performance_score',
        'professionalism_rating',
        'communication_rating',
        'punctuality_rating',
        'manager_comment',
        'director_comment',
        'approved_by_manager_at',
        'approved_by_director_at',
        'status',
    ];

    protected $casts = [
        'approved_by_manager_at' => 'datetime',
        'approved_by_director_at' => 'datetime',
        'performance_score' => 'integer',
    ];

    /**
     * Get the tutor that this assessment is for.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Get the manager who created this assessment.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the director who approved this assessment.
     */
    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    /**
     * Scope to get only draft assessments.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get only submitted assessments.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope to get assessments approved by manager.
     */
    public function scopeApprovedByManager($query)
    {
        return $query->where('status', 'approved-by-manager');
    }

    /**
     * Scope to get assessments approved by director.
     */
    public function scopeApprovedByDirector($query)
    {
        return $query->where('status', 'approved-by-director');
    }

    /**
     * Scope to get assessments pending manager approval.
     */
    public function scopePendingManagerApproval($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope to get assessments pending director approval.
     */
    public function scopePendingDirectorApproval($query)
    {
        return $query->where('status', 'approved-by-manager');
    }

    /**
     * Scope to get assessments for a specific tutor.
     */
    public function scopeForTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }

    /**
     * Check if assessment is in draft status.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if assessment is submitted.
     */
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if assessment is approved by manager.
     */
    public function isManagerApproved()
    {
        return $this->status === 'approved-by-manager';
    }

    /**
     * Check if assessment is approved by director.
     */
    public function isDirectorApproved()
    {
        return $this->status === 'approved-by-director';
    }

    /**
     * Check if assessment is pending director approval.
     */
    public function isPendingDirector()
    {
        return $this->status === 'approved-by-manager';
    }

    /**
     * Check if director can approve this assessment.
     */
    public function canDirectorApprove()
    {
        // Director can approve assessments that are submitted or approved by manager
        return in_array($this->status, ['submitted', 'approved-by-manager']);
    }
}

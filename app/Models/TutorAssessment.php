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
        'student_id',
        'assessment_month',
        'class_date',
        'week',
        'year',
        'session',
        'criteria_assessed',
        'criteria_ratings',
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
        'is_stand_in',
        'original_tutor_id',
    ];

    protected $casts = [
        'class_date' => 'date',
        'approved_by_manager_at' => 'datetime',
        'approved_by_director_at' => 'datetime',
        'performance_score' => 'integer',
        'criteria_assessed' => 'array',
        'criteria_ratings' => 'array',
        'is_stand_in' => 'boolean',
    ];

    /**
     * Get the tutor that this assessment is for (stand-in tutor if applicable).
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Get the original assigned tutor (student's regular tutor) for stand-in assessments.
     */
    public function originalTutor()
    {
        return $this->belongsTo(Tutor::class, 'original_tutor_id');
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
     * Get the student that this assessment is for.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the assessment ratings.
     */
    public function ratings()
    {
        return $this->hasMany(AssessmentRating::class, 'assessment_id');
    }

    /**
     * Get the director action for this assessment.
     */
    public function directorAction()
    {
        return $this->hasOne(DirectorAction::class, 'assessment_id');
    }

    /**
     * Calculate overall score from ratings.
     */
    public function calculateOverallScore(): float
    {
        $ratings = $this->ratings;
        if ($ratings->isEmpty()) {
            return 0;
        }

        $total = $ratings->sum(function ($rating) {
            return $rating->percentage;
        });

        return round($total / $ratings->count(), 1);
    }

    /**
     * Get criteria averages as array.
     */
    public function getCriteriaAveragesAttribute(): array
    {
        $averages = [];
        foreach ($this->ratings as $rating) {
            $averages[$rating->criteria->code] = $rating->percentage;
        }
        return $averages;
    }

    /**
     * Get total penalties from director action.
     */
    public function getTotalPenaltiesAttribute(): float
    {
        return $this->directorAction?->penalty_amount ?? 0;
    }

    /**
     * Get assessment period display string.
     */
    public function getAssessmentPeriodAttribute(): string
    {
        if ($this->class_date) {
            return $this->class_date->format('F j, Y');
        }
        return $this->assessment_month ?? 'N/A';
    }

    /**
     * Get strengths list (criteria >= 75%).
     */
    public function getStrengthsListAttribute(): array
    {
        $criteria = AssessmentCriteria::active()->get()->keyBy('code');
        $strengths = [];

        foreach ($this->criteria_averages as $code => $percentage) {
            if ($percentage >= 75 && isset($criteria[$code])) {
                $strengths[] = [
                    'name' => $criteria[$code]->name,
                    'percentage' => $percentage
                ];
            }
        }

        usort($strengths, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
        return array_slice($strengths, 0, 3);
    }

    /**
     * Get weaknesses list (criteria < 75%).
     */
    public function getWeaknessesListAttribute(): array
    {
        $criteria = AssessmentCriteria::active()->get()->keyBy('code');
        $weaknesses = [];

        foreach ($this->criteria_averages as $code => $percentage) {
            if ($percentage < 75 && isset($criteria[$code])) {
                $weaknesses[] = [
                    'name' => $criteria[$code]->name,
                    'percentage' => $percentage
                ];
            }
        }

        usort($weaknesses, fn($a, $b) => $a['percentage'] <=> $b['percentage']);
        return array_slice($weaknesses, 0, 3);
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
        // Director can approve assessments that are submitted, pending_review, or approved by manager
        return in_array($this->status, ['submitted', 'pending_review', 'approved-by-manager']);
    }

    /**
     * Scope to get assessments pending director review.
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    /**
     * Check if assessment is pending review.
     */
    public function isPendingReview()
    {
        return $this->status === 'pending_review';
    }

    /**
     * Scope to get completed assessments (approved by director).
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'approved-by-director');
    }

    /**
     * Scope to get assessments for a specific student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope to get assessments for a specific year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to get assessments for a specific month.
     */
    public function scopeForMonth($query, int $month)
    {
        return $query->whereMonth('class_date', $month);
    }

    /**
     * Scope to get stand-in assessments.
     */
    public function scopeStandIn($query)
    {
        return $query->where('is_stand_in', true);
    }

    /**
     * Check if this is a stand-in assessment.
     */
    public function isStandIn(): bool
    {
        return (bool) $this->is_stand_in;
    }

    /**
     * Get the display name for the assessed tutor (shows stand-in indicator if applicable).
     */
    public function getAssessedTutorDisplayAttribute(): string
    {
        $tutorName = $this->tutor ? ($this->tutor->first_name . ' ' . $this->tutor->last_name) : 'Unknown';

        if ($this->is_stand_in && $this->originalTutor) {
            $originalName = $this->originalTutor->first_name . ' ' . $this->originalTutor->last_name;
            return $tutorName . ' (Stand-in for ' . $originalName . ')';
        }

        return $tutorName;
    }
}

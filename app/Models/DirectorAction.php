<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'director_id',
        'action_type',
        'penalty_amount',
        'suggested_penalty',
        'director_comment',
        'action_date',
    ];

    protected $casts = [
        'penalty_amount' => 'decimal:2',
        'suggested_penalty' => 'decimal:2',
        'action_date' => 'datetime',
    ];

    /**
     * Get the assessment this action is for.
     */
    public function assessment()
    {
        return $this->belongsTo(TutorAssessment::class, 'assessment_id');
    }

    /**
     * Get the director who performed this action.
     */
    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    /**
     * Get the penalty transaction if any.
     */
    public function penaltyTransaction()
    {
        return $this->hasOne(PenaltyTransaction::class);
    }

    /**
     * Check if this action has a penalty.
     */
    public function hasPenalty(): bool
    {
        return $this->penalty_amount > 0;
    }

    /**
     * Check if penalty was waived.
     */
    public function isPenaltyWaived(): bool
    {
        return $this->action_type === 'approve_no_penalty' && $this->suggested_penalty > 0;
    }
}

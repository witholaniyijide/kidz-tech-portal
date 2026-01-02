<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentCriteria extends Model
{
    use HasFactory;

    protected $table = 'assessment_criteria';

    protected $fillable = [
        'code',
        'name',
        'description',
        'options',
        'penalty_rules',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'penalty_rules' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the ratings for this criteria.
     */
    public function ratings()
    {
        return $this->hasMany(AssessmentRating::class, 'criteria_id');
    }

    /**
     * Scope to get only active criteria.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Get penalty rule for a specific rating.
     */
    public function getPenaltyForRating(string $rating): ?array
    {
        return $this->penalty_rules[$rating] ?? null;
    }

    /**
     * Check if a rating has a penalty.
     */
    public function hasRatingPenalty(string $rating): bool
    {
        return isset($this->penalty_rules[$rating]);
    }
}

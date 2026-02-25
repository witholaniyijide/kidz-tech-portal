<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'criteria_id',
        'rating',
        'score',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    /**
     * Get the assessment this rating belongs to.
     */
    public function assessment()
    {
        return $this->belongsTo(TutorAssessment::class, 'assessment_id');
    }

    /**
     * Get the criteria this rating is for.
     */
    public function criteria()
    {
        return $this->belongsTo(AssessmentCriteria::class, 'criteria_id');
    }

    /**
     * Convert rating to percentage.
     */
    public function getPercentageAttribute(): float
    {
        return match ($this->rating) {
            'Excellent' => 90,
            'On Time' => 90,
            'Good' => 70,
            'Acceptable' => 55,
            'Needs Improvement' => 20,
            'Late' => 20,
            'Unacceptable' => 0,
            default => 0
        };
    }

    /**
     * Get the emoji and label for this rating.
     */
    public function getEmojiAndLabelAttribute(): array
    {
        $percentage = $this->percentage;

        if ($percentage >= 90) {
            return ['emoji' => '🟢', 'label' => 'Excellent'];
        }
        if ($percentage >= 70) {
            return ['emoji' => '🟡', 'label' => 'Good'];
        }
        if ($percentage >= 50) {
            return ['emoji' => '⚙️', 'label' => 'Acceptable'];
        }
        return ['emoji' => '🔴', 'label' => 'Needs Improvement'];
    }

    /**
     * Get visual bar representation.
     */
    public function getVisualBarAttribute(): string
    {
        $totalBlocks = 10;
        $filledBlocks = (int) round(($this->percentage / 100) * $totalBlocks);
        $emptyBlocks = $totalBlocks - $filledBlocks;
        return str_repeat('▓', $filledBlocks) . str_repeat('░', $emptyBlocks);
    }
}

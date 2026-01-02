<?php

/**
 * Convert a rating string to a percentage (0-100).
 */
function ratingToPercentage(string $rating): int
{
    return match ($rating) {
        'Excellent' => 100,
        'On Time' => 100,
        'Good' => 85,
        'Acceptable' => 65,
        'Needs Improvement' => 40,
        'Late' => 40,
        'Unacceptable' => 20,
        default => 0
    };
}

/**
 * Convert a rating string to a score (0-4 scale).
 */
function ratingScore(string $rating): int
{
    return match ($rating) {
        'Excellent', 'On Time' => 4,
        'Good' => 3,
        'Acceptable' => 2,
        'Needs Improvement', 'Late' => 1,
        'Unacceptable' => 0,
        default => 0
    };
}

/**
 * Get emoji and label based on percentage.
 */
function getEmojiAndLabel(float $percentage): array
{
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
 * Create a visual progress bar using block characters.
 */
function createVisualBar(float $percentage): string
{
    $totalBlocks = 10;
    $filledBlocks = (int) round(($percentage / 100) * $totalBlocks);
    $emptyBlocks = $totalBlocks - $filledBlocks;
    return str_repeat('▓', $filledBlocks) . str_repeat('░', $emptyBlocks);
}

/**
 * Get the CSS class for a rating badge.
 */
function getRatingBadgeClass(string $rating): string
{
    return match ($rating) {
        'Excellent', 'On Time' => 'rating-excellent',
        'Good' => 'rating-good',
        'Acceptable' => 'rating-acceptable',
        'Needs Improvement', 'Late' => 'rating-needs-improvement',
        'Unacceptable' => 'rating-unacceptable',
        default => 'bg-gray-100 text-gray-800'
    };
}

/**
 * Get the overall rating CSS class based on percentage.
 */
function getOverallRatingClass(float $percentage): string
{
    if ($percentage >= 90) {
        return 'overall-excellent';
    }
    if ($percentage >= 70) {
        return 'overall-good';
    }
    if ($percentage >= 50) {
        return 'overall-acceptable';
    }
    return 'overall-needs-improvement';
}

/**
 * Get strength summary text.
 */
function getStrengthSummary(array $strengths): string
{
    if (empty($strengths)) {
        return 'All criteria need improvement to reach strength threshold (75%+)';
    }
    $names = array_column($strengths, 'name');
    return 'Strong performance in: ' . implode(', ', $names);
}

/**
 * Get weakness summary text.
 */
function getWeaknessSummary(array $weaknesses): string
{
    if (empty($weaknesses)) {
        return 'All criteria exceed the improvement threshold - excellent work!';
    }
    $names = array_column($weaknesses, 'name');
    return 'Focus needed on: ' . implode(', ', $names);
}

/**
 * Calculate suggested penalty based on ratings and criteria.
 */
function calculateSuggestedPenalty(array $ratings, array $criteriaList): array
{
    $totalPenalty = 0;
    $penaltyDetails = [];

    foreach ($ratings as $rating) {
        $criteria = collect($criteriaList)->firstWhere('code', $rating['criteria_code'] ?? $rating->criteria->code ?? null);
        if (!$criteria) {
            continue;
        }

        $ratingValue = $rating['rating'] ?? $rating->rating;
        $penaltyRules = is_array($criteria) ? ($criteria['penalty_rules'] ?? []) : ($criteria->penalty_rules ?? []);

        if (isset($penaltyRules[$ratingValue])) {
            $rule = $penaltyRules[$ratingValue];
            if (isset($rule['amount'])) {
                $totalPenalty += $rule['amount'];
                $penaltyDetails[] = [
                    'criteria' => is_array($criteria) ? $criteria['name'] : $criteria->name,
                    'rating' => $ratingValue,
                    'amount' => $rule['amount'],
                    'label' => $rule['label'] ?? ''
                ];
            }
        }
    }

    return [
        'total' => $totalPenalty,
        'details' => $penaltyDetails
    ];
}

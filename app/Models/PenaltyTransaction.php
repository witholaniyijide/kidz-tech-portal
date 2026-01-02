<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenaltyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'director_action_id',
        'amount',
        'reason',
        'week_number',
        'year',
        'month',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Get the tutor this penalty is for.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Get the director action that created this penalty.
     */
    public function directorAction()
    {
        return $this->belongsTo(DirectorAction::class);
    }

    /**
     * Scope to get penalties for a specific tutor.
     */
    public function scopeForTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }

    /**
     * Scope to get penalties for a specific month/year.
     */
    public function scopeForPeriod($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Scope to get penalties for a specific year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }
}

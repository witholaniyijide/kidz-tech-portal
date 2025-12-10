<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TutorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'day',
        'start_time',
        'end_time',
        'notes',
        'is_active',
        'type', // 'available' or 'unavailable'
        'specific_date', // For date-specific overrides
        'timezone',
        'google_calendar_id',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'specific_date' => 'date',
    ];

    /**
     * Get the tutor that owns this availability.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Scope to get only active availabilities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get availabilities for a specific day.
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day', $day);
    }

    /**
     * Scope to get weekly recurring availability (not date-specific).
     */
    public function scopeWeekly($query)
    {
        return $query->whereNull('specific_date');
    }

    /**
     * Scope to get date-specific availability.
     */
    public function scopeDateSpecific($query)
    {
        return $query->whereNotNull('specific_date');
    }

    /**
     * Scope to get available slots.
     */
    public function scopeAvailable($query)
    {
        return $query->where('type', 'available');
    }

    /**
     * Scope to get unavailable slots.
     */
    public function scopeUnavailable($query)
    {
        return $query->where('type', 'unavailable');
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute()
    {
        $start = Carbon::parse($this->start_time)->format('g:ia');
        $end = Carbon::parse($this->end_time)->format('g:ia');
        return "{$start} - {$end}";
    }

    /**
     * Check if this slot conflicts with another.
     */
    public function conflictsWith($otherStart, $otherEnd)
    {
        $thisStart = Carbon::parse($this->start_time);
        $thisEnd = Carbon::parse($this->end_time);
        $otherStart = Carbon::parse($otherStart);
        $otherEnd = Carbon::parse($otherEnd);

        return $thisStart < $otherEnd && $otherStart < $thisEnd;
    }
}

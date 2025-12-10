<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'class_date',
        'class_time',
        'duration_minutes',
        'topic',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'is_stand_in',
        'stand_in_reason',
        'is_late',
    ];

    protected $casts = [
        'class_date' => 'date',
        'class_time' => 'datetime',
        'approved_at' => 'datetime',
        'is_stand_in' => 'boolean',
        'is_late' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope to get stand-in attendance records
     */
    public function scopeStandIn($query)
    {
        return $query->where('is_stand_in', true);
    }

    /**
     * Scope to get late submissions
     */
    public function scopeLate($query)
    {
        return $query->where('is_late', true);
    }
}

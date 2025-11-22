<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'other_name',
        'email',
        'phone',
        'date_of_birth',
        'age',
        'gender',
        'address',
        'state',
        'country',
        'parent_name',
        'parent_email',
        'parent_phone',
        'parent_relationship',
        'enrollment_date',
        'coding_experience',
        'career_interest',
        'class_link',
        'google_classroom_link',
        'tutor_id',
        'class_schedule',
        'classes_per_week',
        'total_periods',
        'completed_periods',
        'father_name',
        'father_phone',
        'father_email',
        'father_occupation',
        'father_location',
        'mother_name',
        'mother_phone',
        'mother_email',
        'mother_occupation',
        'mother_location',
        'status',
        'location',
        'notes',
        'profile_photo',
        'parent_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'class_schedule' => 'array',
    ];

    /**
     * Get student's full name
     */
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->other_name) {
            $name .= ' ' . $this->other_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    /**
     * Get student's age
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    /**
     * Scope for active students only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for specific location
     */
    public function scopeLocation($query, $location)
    {
        return $query->where('location', $location);
    }
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}

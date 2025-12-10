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
        'roadmap_stage',
        'roadmap_progress',
        'roadmap_next_milestone',
        'learning_notes',
        'allow_parent_notifications',
        'preferred_contact_method',
        'visible_to_parent',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'class_schedule' => 'array',
        'allow_parent_notifications' => 'boolean',
        'visible_to_parent' => 'boolean',
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

    public function tutorReports()
    {
        return $this->hasMany(TutorReport::class);
    }

    /**
     * Get only director-approved tutor reports for this student.
     * These are visible to parents in the parent dashboard.
     */
    public function approvedReports()
    {
        return $this->hasMany(TutorReport::class)
                    ->where('status', 'approved-by-director')
                    ->orderBy('created_at', 'desc');
    }

    public function fullName()
    {
        $name = $this->first_name;
        if ($this->other_name) {
            $name .= ' ' . $this->other_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    /**
     * Get the guardians (parents) associated with this student
     */
    public function guardians()
    {
        return $this->belongsToMany(User::class, 'guardian_student', 'student_id', 'user_id')
                    ->withPivot('relationship', 'primary_contact')
                    ->withTimestamps();
    }

    /**
     * Get all progress items for this student
     */
    public function progress()
    {
        return $this->hasMany(StudentProgress::class);
    }

    /**
     * Get portal settings for this student
     */
    public function portalSettings()
    {
        return $this->hasOne(StudentPortalSetting::class);
    }

    /**
     * Get the primary guardian for this student
     */
    public function primaryGuardian()
    {
        return $this->guardians()->wherePivot('primary_contact', true)->first();
    }

    /**
     * Get the student's progress percentage
     */
    public function progressPercentage()
    {
        // Return roadmap_progress if set, otherwise calculate from progress items
        if ($this->roadmap_progress !== null) {
            return $this->roadmap_progress;
        }

        $total = $this->progress()->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->progress()->where('completed', true)->count();
        return (int) (($completed / $total) * 100);
    }
}

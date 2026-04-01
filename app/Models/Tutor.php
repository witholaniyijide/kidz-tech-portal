<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tutor_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'state',
        'location',
        'occupation',
        'bio',
        'hire_date',
        'contact_person_name',
        'contact_person_relationship',
        'contact_person_phone',
        'bank_name',
        'account_number',
        'account_name',
        'status',
        'resigned_at',
        'hourly_rate',
        'qualifications',
        'specialization',
        'notes',
        'profile_photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'resigned_at' => 'datetime',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Alias for attendanceRecords - used by views
     */
    public function attendances()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function reports()
    {
        return $this->hasMany(TutorReport::class);
    }

    /**
     * Alias for reports - used by manager views
     */
    public function monthlyReports()
    {
        return $this->hasMany(TutorReport::class);
    }

    /**
     * Get tutor assessments
     */
    public function tutorAssessments()
    {
        return $this->hasMany(TutorAssessment::class);
    }

    /**
     * Alias for tutorAssessments - for assessments relationship
     */
    public function assessments()
    {
        return $this->hasMany(TutorAssessment::class);
    }

    public function notifications()
    {
        return $this->hasMany(TutorNotification::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TutorAvailability::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get count of active students only
     */
    public function studentCount()
    {
        return $this->students()->where('status', 'active')->count();
    }

    /**
     * Get count of inactive students
     */
    public function inactiveStudentCount()
    {
        return $this->students()->where('status', 'inactive')->count();
    }

    /**
     * Get active students relationship
     */
    public function activeStudents()
    {
        return $this->hasMany(Student::class)->where('status', 'active');
    }

    /**
     * Get inactive students relationship
     */
    public function inactiveStudents()
    {
        return $this->hasMany(Student::class)->where('status', 'inactive');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOnLeave($query)
    {
        return $query->where('status', 'on_leave');
    }

    public function scopeResigned($query)
    {
        return $query->where('status', 'resigned');
    }

    /**
     * Scope to exclude resigned tutors from counts
     */
    public function scopeNotResigned($query)
    {
        return $query->where('status', '!=', 'resigned');
    }

    /**
     * Check if tutor is resigned and access should be restricted (3 days after resignation)
     */
    public function isAccessRestricted(): bool
    {
        if ($this->status !== 'resigned') {
            return false;
        }

        if (!$this->resigned_at) {
            return false;
        }

        return $this->resigned_at->diffInDays(now()) >= 3;
    }
}

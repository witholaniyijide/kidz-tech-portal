<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Tutor extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

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

    public function studentCount()
    {
        return $this->students()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOnLeave($query)
    {
        return $query->where('status', 'on_leave');
    }
}

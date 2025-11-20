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
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'state',
        'country',
        'parent_name',
        'parent_email',
        'parent_phone',
        'parent_relationship',
        'enrollment_date',
        'status',
        'location',
        'notes',
        'profile_photo',
        'parent_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    /**
     * Get student's full name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
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
}

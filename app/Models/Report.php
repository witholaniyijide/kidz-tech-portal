<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'instructor_id',
        'month',
        'year',
        'courses',
        'skills_mastered',
        'skills_new',
        'projects',
        'improvement',
        'goals',
        'assignments',
        'comments',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'courses' => 'array',
        'skills_mastered' => 'array',
        'skills_new' => 'array',
        'projects' => 'array',
        'approved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}

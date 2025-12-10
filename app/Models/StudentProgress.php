<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $table = 'student_progress';

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'milestone_code',
        'completed',
        'completed_at',
        'points',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the student that owns this progress item
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

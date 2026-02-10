<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'name',
        'full_name',
        'description',
        'expected_classes',
        'certificate_eligible',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'expected_classes' => 'integer',
        'certificate_eligible' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope for active courses only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by level.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('level', 'asc');
    }

    /**
     * Get students who have completed this course.
     */
    public function studentsCompleted()
    {
        return $this->belongsToMany(Student::class, 'student_course_progress')
            ->withPivot(['status', 'source', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Get students who started with this course.
     */
    public function studentsStartedWith()
    {
        return $this->hasMany(Student::class, 'starting_course_id');
    }

    /**
     * Get students currently on this course.
     */
    public function studentsCurrentlyOn()
    {
        return $this->hasMany(Student::class, 'current_course_id');
    }

    /**
     * Get course by level number.
     */
    public static function findByLevel(int $level): ?self
    {
        return static::where('level', $level)->first();
    }

    /**
     * Get all courses as options for dropdowns.
     */
    public static function getDropdownOptions(): array
    {
        return static::active()
            ->ordered()
            ->get()
            ->pluck('full_name', 'id')
            ->toArray();
    }
}

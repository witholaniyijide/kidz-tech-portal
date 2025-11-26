<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPortalSetting extends Model
{
    use HasFactory;

    protected $table = 'student_portal_settings';

    protected $fillable = [
        'student_id',
        'timezone',
        'preferred_language',
        'show_roadmap_public',
    ];

    protected $casts = [
        'show_roadmap_public' => 'boolean',
    ];

    /**
     * Get the student that owns these settings
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

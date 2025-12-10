<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'uploaded_by',
        'certificate_id',
        'title',
        'course_name',
        'milestone_name',
        'description',
        'file_path',
        'file_type',
        'issue_date',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate unique certificate ID on creation
        static::creating(function ($certification) {
            if (empty($certification->certificate_id)) {
                $certification->certificate_id = static::generateCertificateId();
            }
        });
    }

    /**
     * Generate a unique certificate ID.
     * Format: KTCC-YYYY-XXXXXX (e.g., KTCC-2024-A3B7C9)
     */
    public static function generateCertificateId(): string
    {
        $year = date('Y');
        $random = strtoupper(Str::random(6));
        $certificateId = "KTCC-{$year}-{$random}";

        // Ensure uniqueness
        while (static::where('certificate_id', $certificateId)->exists()) {
            $random = strtoupper(Str::random(6));
            $certificateId = "KTCC-{$year}-{$random}";
        }

        return $certificateId;
    }

    /**
     * Get the student this certificate belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who uploaded this certificate.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope for active certificates only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for certificates of a specific student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Check if the certificate is valid (active and not expired).
     */
    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get the full URL to the certificate file.
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}

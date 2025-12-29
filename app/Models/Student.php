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
        'live_classroom_link',
        'current_level',
        'starting_course_level',
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
        'course_statuses',
        'class_reminder_enabled',
        'class_reminder_minutes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'class_schedule' => 'array',
        'allow_parent_notifications' => 'boolean',
        'visible_to_parent' => 'boolean',
        'course_statuses' => 'array',
        'class_reminder_enabled' => 'boolean',
    ];

    /**
     * Default attribute values for legacy fields
     */
    protected $attributes = [
        'parent_name' => '',
        'parent_email' => '',
        'parent_phone' => '',
        'parent_relationship' => '',
    ];

    /**
     * Boot the model and register event handlers
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            // Auto-generate student_id if not provided
            if (empty($student->student_id)) {
                $student->student_id = 'STU-' . strtoupper(uniqid());
            }

            // Set default empty string for legacy NOT NULL fields if not set
            $legacyFields = ['parent_name', 'parent_email', 'parent_phone', 'parent_relationship'];
            foreach ($legacyFields as $field) {
                if (!isset($student->$field)) {
                    $student->$field = '';
                }
            }

            // Set enrollment_date to today if not provided
            if (empty($student->enrollment_date)) {
                $student->enrollment_date = now();
            }
        });
    }

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

    /**
     * Alias for attendanceRecords - used by views
     */
    public function attendances()
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
     * Alias for tutorReports - used by manager views
     */
    public function monthlyReports()
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
     * Get all certifications for this student
     */
    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    /**
     * Get active certifications for this student
     */
    public function activeCertifications()
    {
        return $this->hasMany(Certification::class)->where('status', 'active');
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
        // Get course statuses which accounts for starting_course_level
        $courseStatuses = $this->calculateCourseStatuses();

        // Count completed courses
        $completedCount = 0;
        foreach ($courseStatuses as $status) {
            if ($status === 'completed') {
                $completedCount++;
            }
        }

        // Calculate percentage (12 total courses)
        $totalCourses = 12;
        return $totalCourses > 0 ? (int) (($completedCount / $totalCourses) * 100) : 0;
    }

    /**
     * Calculate course progress from approved reports.
     * Returns array with completed courses, in-progress course, and percentage.
     */
    public function calculateProgressFromReports()
    {
        // Define the standard course order (levels)
        $allCourses = [
            'Level 1 - Introduction to Computer Science',
            'Level 2 - Coding and Fundamental Concepts',
            'Level 3 - Scratch Programming',
            'Level 4 - Artificial Intelligence',
            'Level 5 - Graphics Design',
            'Level 6 - Game Development',
            'Level 7 - Mobile App Development',
            'Level 8 - Website Development',
            'Level 9 - Python Programming',
            'Level 10 - Digital Literacy & Safety',
            'Level 11 - Machine Learning',
            'Level 12 - Robotics',
        ];

        $totalCourses = count($allCourses);

        // Get all approved reports for this student, ordered by date
        $reports = $this->tutorReports()
            ->whereIn('status', ['approved-by-manager', 'approved-by-director'])
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        if ($reports->isEmpty()) {
            return [
                'completed_courses' => [],
                'in_progress_course' => null,
                'overall_percentage' => 0,
                'total_courses' => $totalCourses,
                'completed_count' => 0,
            ];
        }

        $completedCourses = [];
        $inProgressCourse = null;

        foreach ($reports as $report) {
            $courses = $report->courses;
            if (is_string($courses)) {
                $courses = json_decode($courses, true) ?? [];
            }
            if (!is_array($courses)) {
                $courses = [];
            }

            if (count($courses) > 0) {
                // If multiple courses in one report, all but the last are completed
                if (count($courses) > 1) {
                    for ($i = 0; $i < count($courses) - 1; $i++) {
                        if (!in_array($courses[$i], $completedCourses)) {
                            $completedCourses[] = $courses[$i];
                        }
                    }
                }
                // The last course in the report is the current/in-progress one
                $inProgressCourse = end($courses);
            }
        }

        // Mark courses as completed if they were followed by another course in later reports
        // If the in-progress course appears in an earlier report and then another course appears,
        // it means that course was completed
        $allCoursesFromReports = [];
        foreach ($reports as $report) {
            $courses = $report->courses;
            if (is_string($courses)) {
                $courses = json_decode($courses, true) ?? [];
            }
            if (is_array($courses)) {
                foreach ($courses as $course) {
                    if (!in_array($course, $allCoursesFromReports)) {
                        $allCoursesFromReports[] = $course;
                    }
                }
            }
        }

        // If there are multiple unique courses across all reports,
        // all but the last one in the list are completed
        if (count($allCoursesFromReports) > 1) {
            for ($i = 0; $i < count($allCoursesFromReports) - 1; $i++) {
                if (!in_array($allCoursesFromReports[$i], $completedCourses)) {
                    $completedCourses[] = $allCoursesFromReports[$i];
                }
            }
            $inProgressCourse = end($allCoursesFromReports);
        }

        // Calculate percentage: completed courses / total courses * 100
        $completedCount = count($completedCourses);
        $overallPercentage = $totalCourses > 0 ? (int) (($completedCount / $totalCourses) * 100) : 0;

        return [
            'completed_courses' => $completedCourses,
            'in_progress_course' => $inProgressCourse,
            'overall_percentage' => $overallPercentage,
            'total_courses' => $totalCourses,
            'completed_count' => $completedCount,
        ];
    }

    /**
     * Get the curriculum courses with their statuses.
     * Returns array with status for each course: completed, ongoing, not_started
     */
    public function getCurriculumWithStatuses(): array
    {
        $allCourses = [
            1 => 'Introduction to Computer Science',
            2 => 'Coding & Fundamental Concepts',
            3 => 'Scratch Programming',
            4 => 'Artificial Intelligence',
            5 => 'Graphic Design',
            6 => 'Game Development',
            7 => 'Mobile App Development',
            8 => 'Website Development',
            9 => 'Python Programming',
            10 => 'Digital Literacy & Safety/Security',
            11 => 'Machine Learning',
            12 => 'Robotics',
        ];

        // Get stored course statuses or calculate from starting_course_level and reports
        $courseStatuses = $this->course_statuses;

        // If no course_statuses stored, calculate from starting_course_level and progress reports
        if (empty($courseStatuses)) {
            $courseStatuses = $this->calculateCourseStatuses();
        }

        $result = [];
        foreach ($allCourses as $id => $title) {
            $status = $courseStatuses[$id] ?? 'not_started';
            $result[] = [
                'id' => $id,
                'title' => $title,
                'status' => $status,
            ];
        }

        return $result;
    }

    /**
     * Calculate course statuses based on starting_course_level and approved reports.
     */
    public function calculateCourseStatuses(): array
    {
        $statuses = [];

        // Get starting course level (default is 1)
        $startingLevel = $this->starting_course_level ?? 1;

        // Mark all courses before starting level as completed (they were done before joining)
        for ($i = 1; $i < $startingLevel; $i++) {
            $statuses[$i] = 'completed';
        }

        // Get progress from approved reports
        $progress = $this->calculateProgressFromReports();

        // Mark courses from reports as completed or ongoing
        foreach ($progress['completed_courses'] as $courseName) {
            // Extract level number from course name
            if (preg_match('/Level\s*(\d+)/i', $courseName, $matches)) {
                $level = (int) $matches[1];
                $statuses[$level] = 'completed';
            }
        }

        // Mark current course as ongoing
        if ($progress['in_progress_course']) {
            if (preg_match('/Level\s*(\d+)/i', $progress['in_progress_course'], $matches)) {
                $level = (int) $matches[1];
                $statuses[$level] = 'ongoing';
            }
        }

        // If no in-progress course, mark starting level as ongoing (if not already completed)
        if (!$progress['in_progress_course'] && !isset($statuses[$startingLevel])) {
            $statuses[$startingLevel] = 'ongoing';
        }

        // Mark remaining courses as not_started
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($statuses[$i])) {
                $statuses[$i] = 'not_started';
            }
        }

        return $statuses;
    }

    /**
     * Get course progress details for display.
     */
    public function getCourseProgressDetails()
    {
        $progress = $this->calculateProgressFromReports();

        $allCourses = [
            'Level 1 - Introduction to Computer Science',
            'Level 2 - Coding and Fundamental Concepts',
            'Level 3 - Scratch Programming',
            'Level 4 - Artificial Intelligence',
            'Level 5 - Graphics Design',
            'Level 6 - Game Development',
            'Level 7 - Mobile App Development',
            'Level 8 - Website Development',
            'Level 9 - Python Programming',
            'Level 10 - Digital Literacy & Safety',
            'Level 11 - Machine Learning',
            'Level 12 - Robotics',
        ];

        $courseDetails = [];
        foreach ($allCourses as $course) {
            $status = 'not_started';
            if (in_array($course, $progress['completed_courses'])) {
                $status = 'completed';
            } elseif ($course === $progress['in_progress_course']) {
                $status = 'in_progress';
            }

            $courseDetails[] = [
                'name' => $course,
                'status' => $status,
            ];
        }

        return [
            'courses' => $courseDetails,
            'overall_percentage' => $progress['overall_percentage'],
            'completed_count' => $progress['completed_count'],
            'in_progress_course' => $progress['in_progress_course'],
        ];
    }
}

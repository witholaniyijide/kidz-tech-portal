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
        'starting_course_id',
        'current_course_id',
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

        // Auto-calculate classes_per_week from class_schedule
        static::saving(function ($student) {
            if (is_array($student->class_schedule)) {
                $validSchedules = array_filter($student->class_schedule, function ($schedule) {
                    return !empty($schedule['day']) && trim($schedule['day']) !== '';
                });
                $student->classes_per_week = count($validSchedules);
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
        return $this->date_of_birth ? $this->date_of_birth->age : null;
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

    /**
     * Get the starting course for this student.
     */
    public function startingCourse()
    {
        return $this->belongsTo(Course::class, 'starting_course_id');
    }

    /**
     * Get the current course for this student.
     */
    public function currentCourse()
    {
        return $this->belongsTo(Course::class, 'current_course_id');
    }

    /**
     * Get explicitly completed courses for this student.
     */
    public function completedCourses()
    {
        return $this->belongsToMany(Course::class, 'student_course_progress')
            ->withPivot(['status', 'source', 'completed_at'])
            ->withTimestamps()
            ->orderBy('level', 'asc');
    }

    /**
     * Get the course progress records for this student.
     */
    public function courseProgress()
    {
        return $this->hasMany(StudentCourseProgress::class);
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
            // Extract level number from course name (ensure string type for preg_match)
            $courseName = is_string($courseName) ? $courseName : (string) ($courseName ?? '');
            if (preg_match('/Level\s*(\d+)/i', $courseName, $matches)) {
                $level = (int) $matches[1];
                $statuses[$level] = 'completed';
            }
        }

        // Mark current course as ongoing
        if ($progress['in_progress_course']) {
            $inProgress = is_string($progress['in_progress_course']) ? $progress['in_progress_course'] : (string) ($progress['in_progress_course'] ?? '');
            if (preg_match('/Level\s*(\d+)/i', $inProgress, $matches)) {
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

    // =====================================================
    // EXPLICIT COURSE PROGRESSION SYSTEM (New Implementation)
    // =====================================================

    /**
     * Mark a course as completed for this student (explicit).
     *
     * @param int $courseId
     * @param string $source 'manual' or 'report'
     * @return StudentCourseProgress
     */
    public function markCourseCompleted(int $courseId, string $source = 'manual'): StudentCourseProgress
    {
        return StudentCourseProgress::markCompleted($this->id, $courseId, $source);
    }

    /**
     * Remove a completed course for this student.
     *
     * @param int $courseId
     * @return bool
     */
    public function removeCourseCompletion(int $courseId): bool
    {
        return StudentCourseProgress::removeCompletion($this->id, $courseId);
    }

    /**
     * Check if this student has explicitly completed a course.
     *
     * @param int $courseId
     * @return bool
     */
    public function hasCourseCompleted(int $courseId): bool
    {
        return StudentCourseProgress::hasCompleted($this->id, $courseId);
    }

    /**
     * Set the starting course (immutable after first set).
     *
     * @param int $courseId
     * @return bool
     */
    public function setStartingCourse(int $courseId): bool
    {
        // Only allow setting once
        if ($this->starting_course_id !== null) {
            return false;
        }

        $this->starting_course_id = $courseId;
        return $this->save();
    }

    /**
     * Set the current course.
     *
     * @param int|null $courseId
     * @return bool
     */
    public function setCurrentCourse(?int $courseId): bool
    {
        $this->current_course_id = $courseId;
        return $this->save();
    }

    /**
     * Sync completed courses (replace all with given IDs).
     *
     * @param array $courseIds
     * @param string $source
     * @return void
     */
    public function syncCompletedCourses(array $courseIds, string $source = 'manual'): void
    {
        // Get current completed course IDs
        $currentIds = $this->completedCourses()->pluck('courses.id')->toArray();

        // Courses to remove
        $toRemove = array_diff($currentIds, $courseIds);
        foreach ($toRemove as $courseId) {
            $this->removeCourseCompletion($courseId);
        }

        // Courses to add
        $toAdd = array_diff($courseIds, $currentIds);
        foreach ($toAdd as $courseId) {
            $this->markCourseCompleted($courseId, $source);
        }
    }

    /**
     * Get explicit curriculum with statuses using the new system.
     * This replaces the old calculateCourseStatuses() for parent portal.
     *
     * @return array
     */
    public function getExplicitCurriculumWithStatuses(): array
    {
        $courses = Course::active()->ordered()->get();
        $completedIds = $this->completedCourses()->pluck('courses.id')->toArray();
        $currentId = $this->current_course_id;

        $result = [];
        foreach ($courses as $course) {
            $status = 'not_started';

            if (in_array($course->id, $completedIds)) {
                $status = 'completed';
            } elseif ($course->id === $currentId) {
                $status = 'ongoing';
            }

            $result[] = [
                'id' => $course->id,
                'level' => $course->level,
                'title' => $course->name,
                'full_name' => $course->full_name,
                'status' => $status,
                'certificate_eligible' => $course->certificate_eligible,
            ];
        }

        return $result;
    }

    /**
     * Calculate explicit progress percentage.
     * Only counts explicitly completed courses, not skipped ones.
     *
     * @return int
     */
    public function getExplicitProgressPercentage(): int
    {
        $completedCount = $this->completedCourses()->count();
        $totalCourses = 12;

        return $totalCourses > 0 ? (int) (($completedCount / $totalCourses) * 100) : 0;
    }

    /**
     * Count approved attendance records for a specific course.
     *
     * @param int $courseId The course ID (level number 1-12)
     * @return int Number of approved attendance records for this course
     */
    public function getAttendanceCountForCourse(int $courseId): int
    {
        // Build the course prefix pattern (e.g., "01 - ", "02 - ")
        $coursePrefix = str_pad($courseId, 2, '0', STR_PAD_LEFT) . ' - ';

        // Get the course to also match by title
        $course = Course::findByLevel($courseId);
        $courseTitle = $course?->full_name;

        // Get all approved attendance records for this student
        $records = AttendanceRecord::where('student_id', $this->id)
            ->where('status', 'approved')
            ->get();

        $count = 0;

        foreach ($records as $record) {
            $courses = $record->courses_covered;

            // Handle different formats of courses_covered
            if (is_string($courses)) {
                $courses = json_decode($courses, true) ?? [$courses];
            }

            // Skip if no courses data
            if (!is_array($courses) || empty($courses)) {
                continue;
            }

            // Check if any course matches our target
            foreach ($courses as $courseName) {
                if (str_starts_with($courseName, $coursePrefix)) {
                    $count++;
                    break;
                }
                if ($courseTitle && stripos($courseName, $courseTitle) !== false) {
                    $count++;
                    break;
                }
            }
        }

        return $count;
    }

    /**
     * Get attendance-based progress percentage for the current course.
     * Returns percentage based on (attended classes / expected classes) * 100
     *
     * @return int Progress percentage (0-100)
     */
    public function getAttendanceBasedProgress(): int
    {
        // Only works for explicit progression system with a current course
        if (!$this->usesExplicitProgression() || !$this->current_course_id) {
            return $this->roadmap_progress ?? 0;
        }

        // Get the current course
        $currentCourse = $this->currentCourse;
        if (!$currentCourse) {
            return $this->roadmap_progress ?? 0;
        }

        // Count attendance for this course
        $attendanceCount = $this->getAttendanceCountForCourse($currentCourse->level);

        // Get expected classes (default to 8 if not set)
        $expectedClasses = $currentCourse->expected_classes ?? 8;

        // Calculate percentage (cap at 100)
        $percentage = $expectedClasses > 0
            ? min(100, (int) (($attendanceCount / $expectedClasses) * 100))
            : 0;

        return $percentage;
    }

    /**
     * Check if the current course should be auto-completed based on attendance.
     * Returns true if attendance count >= expected classes.
     *
     * @return bool
     */
    public function shouldAutoCompleteCourse(): bool
    {
        if (!$this->usesExplicitProgression() || !$this->current_course_id) {
            return false;
        }

        $currentCourse = $this->currentCourse;
        if (!$currentCourse) {
            return false;
        }

        $attendanceCount = $this->getAttendanceCountForCourse($currentCourse->level);
        $expectedClasses = $currentCourse->expected_classes ?? 8;

        return $attendanceCount >= $expectedClasses;
    }

    /**
     * Auto-complete the current course if attendance threshold is met.
     * Marks the course as completed but does NOT advance to next course.
     * Admin must manually set the next current_course_id.
     *
     * @return bool True if course was auto-completed, false otherwise
     */
    public function autoCompleteCourseIfReady(): bool
    {
        if (!$this->shouldAutoCompleteCourse()) {
            return false;
        }

        $currentCourse = $this->currentCourse;
        if (!$currentCourse) {
            return false;
        }

        // Check if already completed
        if ($this->hasCourseCompleted($currentCourse->id)) {
            return false;
        }

        // Mark course as completed (source: 'attendance' to indicate auto-completion)
        $this->markCourseCompleted($currentCourse->id, 'attendance');

        // Set current_course_id to null to await admin to set next course
        $this->current_course_id = null;
        $this->roadmap_progress = 0;
        $this->save();

        return true;
    }

    /**
     * Get the current course progress considering both attendance and manual progress.
     * Returns the higher of attendance-based or manually set progress.
     *
     * @return int Progress percentage (0-100)
     */
    public function getCurrentCourseProgress(): int
    {
        $attendanceProgress = $this->getAttendanceBasedProgress();
        $manualProgress = $this->roadmap_progress ?? 0;

        // Return the higher value to prevent regression
        return max($attendanceProgress, $manualProgress);
    }

    /**
     * Check if using the new explicit progression system.
     * Returns true if starting_course_id is set.
     *
     * @return bool
     */
    public function usesExplicitProgression(): bool
    {
        return $this->starting_course_id !== null;
    }
}

<?php

namespace App\Services;

use App\Models\Certification;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\TutorReport;

class StudentProfileService
{
    /**
     * Get complete student profile data for parent view.
     */
    public function getProfileData(Student $student): array
    {
        $student->load(['tutor']);

        return [
            'basic_info' => $this->getBasicInfo($student),
            'enrollment_info' => $this->getEnrollmentInfo($student),
            'class_info' => $this->getClassInfo($student),
            'tutor_info' => $this->getTutorInfo($student),
            'progress_info' => $this->getProgressInfo($student),
            'stats' => $this->getStats($student),
        ];
    }

    /**
     * Get basic student information.
     */
    public function getBasicInfo(Student $student): array
    {
        return [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'full_name' => $student->full_name,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'other_name' => $student->other_name,
            'email' => $student->email,
            'phone' => $student->phone,
            'date_of_birth' => $student->date_of_birth?->format('F d, Y'),
            'age' => $student->date_of_birth?->age,
            'gender' => $student->gender,
            'profile_photo' => $student->profile_photo,
            'status' => $student->status,
        ];
    }

    /**
     * Get enrollment information.
     */
    public function getEnrollmentInfo(Student $student): array
    {
        return [
            'enrollment_date' => $student->enrollment_date?->format('F d, Y'),
            'coding_experience' => $student->coding_experience,
            'career_interest' => $student->career_interest,
            'location' => $student->location,
            'address' => $student->address,
            'state' => $student->state,
            'country' => $student->country,
        ];
    }

    /**
     * Get class information.
     */
    public function getClassInfo(Student $student): array
    {
        $schedule = $student->class_schedule ?? [];
        $formattedSchedule = $this->formatClassSchedule($schedule);

        return [
            'class_link' => $student->class_link,
            'google_classroom_link' => $student->google_classroom_link,
            'class_schedule' => $formattedSchedule,
            'classes_per_week' => $student->classes_per_week,
            'total_periods' => $student->total_periods,
            'completed_periods' => $student->completed_periods,
        ];
    }

    /**
     * Get tutor information.
     */
    public function getTutorInfo(Student $student): ?array
    {
        $tutor = $student->tutor;

        if (!$tutor) {
            return null;
        }

        return [
            'id' => $tutor->id,
            'full_name' => $tutor->first_name . ' ' . $tutor->last_name,
            'first_name' => $tutor->first_name,
            'last_name' => $tutor->last_name,
            'email' => $tutor->email,
            'phone' => $tutor->phone,
            'profile_photo' => $tutor->profile_photo,
            'bio' => $tutor->bio,
            'specialization' => $tutor->specialization,
        ];
    }

    /**
     * Get progress information.
     */
    public function getProgressInfo(Student $student): array
    {
        return [
            'progress_percentage' => $student->progressPercentage(),
            'roadmap_stage' => $student->roadmap_stage ?? 1,
            'roadmap_progress' => $student->roadmap_progress ?? 0,
            'roadmap_next_milestone' => $student->roadmap_next_milestone,
            'learning_notes' => $student->visible_to_parent ? $student->learning_notes : null,
        ];
    }

    /**
     * Get stats for the student.
     */
    public function getStats(Student $student): array
    {
        // Count approved reports
        $reportsCount = TutorReport::where('student_id', $student->id)
            ->where('status', 'approved-by-director')
            ->count();

        // Count certifications
        $certificationsCount = Certification::where('student_id', $student->id)
            ->where('status', 'active')
            ->count();

        // Count completed milestones
        $milestonesCount = StudentProgress::where('student_id', $student->id)
            ->where('completed', true)
            ->count();

        // Total XP points
        $totalPoints = StudentProgress::where('student_id', $student->id)
            ->where('completed', true)
            ->sum('points');

        return [
            'reports_count' => $reportsCount,
            'certifications_count' => $certificationsCount,
            'milestones_completed' => $milestonesCount,
            'total_points' => $totalPoints,
        ];
    }

    /**
     * Format class schedule for display.
     */
    private function formatClassSchedule(array $schedule): array
    {
        $formatted = [];

        foreach ($schedule as $item) {
            if (is_array($item)) {
                $formatted[] = [
                    'day' => $item['day'] ?? '',
                    'time' => $item['time'] ?? '',
                    'timezone' => $item['timezone'] ?? 'GMT+1',
                ];
            } elseif (is_string($item)) {
                $formatted[] = ['schedule' => $item];
            }
        }

        return $formatted;
    }

    /**
     * Get curriculum roadmap for a student.
     */
    public function getCurriculumRoadmap(Student $student): array
    {
        $courses = [
            ['id' => 1, 'title' => 'Introduction to Computer Science', 'icon' => 'computer', 'description' => 'Learn the basics of computer science and computational thinking.'],
            ['id' => 2, 'title' => 'Coding & Fundamental Concepts', 'icon' => 'code', 'description' => 'Understand core programming concepts like variables, loops, and functions.'],
            ['id' => 3, 'title' => 'Scratch Programming', 'icon' => 'puzzle', 'description' => 'Create interactive stories, games, and animations using Scratch.'],
            ['id' => 4, 'title' => 'Artificial Intelligence', 'icon' => 'brain', 'description' => 'Explore the fundamentals of AI and machine learning concepts.'],
            ['id' => 5, 'title' => 'Graphic Design', 'icon' => 'palette', 'description' => 'Learn design principles and create stunning digital artwork.'],
            ['id' => 6, 'title' => 'Game Development', 'icon' => 'gamepad', 'description' => 'Build your own games using popular game engines.'],
            ['id' => 7, 'title' => 'Mobile App Development', 'icon' => 'smartphone', 'description' => 'Create mobile applications for iOS and Android platforms.'],
            ['id' => 8, 'title' => 'Website Development', 'icon' => 'globe', 'description' => 'Build modern websites using HTML, CSS, and JavaScript.'],
            ['id' => 9, 'title' => 'Python Programming', 'icon' => 'terminal', 'description' => 'Master Python programming for automation and data analysis.'],
            ['id' => 10, 'title' => 'Digital Literacy & Safety/Security', 'icon' => 'shield', 'description' => 'Learn online safety, privacy, and cybersecurity basics.'],
            ['id' => 11, 'title' => 'Machine Learning', 'icon' => 'cpu', 'description' => 'Dive into machine learning algorithms and neural networks.'],
            ['id' => 12, 'title' => 'Robotics', 'icon' => 'robot', 'description' => 'Build and program robots using various platforms.'],
        ];

        $currentStage = $student->roadmap_stage ?? 1;
        $progress = $student->roadmap_progress ?? 0;

        foreach ($courses as $key => $course) {
            if ($course['id'] < $currentStage) {
                $courses[$key]['status'] = 'completed';
                $courses[$key]['progress'] = 100;
            } elseif ($course['id'] == $currentStage) {
                $courses[$key]['status'] = 'current';
                $courses[$key]['progress'] = $progress;
            } else {
                $courses[$key]['status'] = 'upcoming';
                $courses[$key]['progress'] = 0;
            }
        }

        return $courses;
    }
}

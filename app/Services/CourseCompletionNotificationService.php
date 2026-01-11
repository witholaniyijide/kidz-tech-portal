<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Course;
use App\Models\ParentNotification;
use App\Models\User;

class CourseCompletionNotificationService
{
    /**
     * Send course completion notification to parent(s).
     *
     * @param Student $student
     * @param int $courseId
     * @return void
     */
    public function notify(Student $student, int $courseId): void
    {
        $course = Course::find($courseId);
        if (!$course) {
            return;
        }

        $studentName = $student->first_name;
        $courseName = $course->name;

        // Notification message
        $message = "Congratulations {$studentName}! You have successfully completed {$courseName}.";

        // Add certificate info for courses 2-12
        if ($course->certificate_eligible) {
            $message .= " A certificate will be awarded for this achievement.";
        }

        // Get parent guardians for this student
        $guardians = $student->guardians;

        foreach ($guardians as $guardian) {
            $this->createNotification($guardian, $student, $course, $message);
        }

        // Also notify via parent_id if set (legacy support)
        if ($student->parent_id) {
            $parent = User::find($student->parent_id);
            if ($parent && !$guardians->contains('id', $parent->id)) {
                $this->createNotification($parent, $student, $course, $message);
            }
        }
    }

    /**
     * Create a notification record for a user.
     *
     * @param User $user
     * @param Student $student
     * @param Course $course
     * @param string $message
     * @return void
     */
    protected function createNotification(User $user, Student $student, Course $course, string $message): void
    {
        ParentNotification::create([
            'parent_id' => $user->id,
            'student_id' => $student->id,
            'type' => 'course_completion',
            'title' => "Course Completed: {$course->name}",
            'message' => $message,
            'link' => route('parent.children.show', $student->id),
            'data' => [
                'student_name' => $student->fullName(),
                'course_id' => $course->id,
                'course_name' => $course->name,
                'course_level' => $course->level,
                'certificate_eligible' => $course->certificate_eligible,
            ],
        ]);
    }
}

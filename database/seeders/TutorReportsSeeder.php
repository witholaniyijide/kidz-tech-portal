<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\Student;
use App\Models\User;
use App\Models\TutorReport;
use App\Models\TutorReportComment;
use App\Models\TutorAvailability;
use App\Models\TutorNotification;
use Illuminate\Database\Seeder;

class TutorReportsSeeder extends Seeder
{
    public function run(): void
    {
        // Get all tutors and students
        $tutors = Tutor::all();
        $students = Student::all();
        $managers = User::whereHas('roles', function($query) {
            $query->where('name', 'manager');
        })->get();

        if ($tutors->isEmpty() || $students->isEmpty()) {
            $this->command->warn('Please run TutorsSeeder and StudentsSeeder first!');
            return;
        }

        // Create sample reports for each tutor
        foreach ($tutors->take(3) as $index => $tutor) {
            // Get the user associated with the tutor
            $tutorUser = User::where('email', $tutor->email)->first();

            if (!$tutorUser) {
                continue;
            }

            // Create 2-3 reports per tutor
            $reportStatuses = ['draft', 'submitted', 'manager_review'];

            for ($i = 1; $i <= 3; $i++) {
                $student = $students->random();
                $status = $reportStatuses[$i - 1] ?? 'draft';

                $report = TutorReport::create([
                    'tutor_id' => $tutor->id,
                    'student_id' => $student->id,
                    'title' => "Monthly Progress Report - {$student->first_name} {$student->last_name}",
                    'month' => now()->subMonths($i)->format('F Y'),
                    'period_from' => now()->subMonths($i)->startOfMonth(),
                    'period_to' => now()->subMonths($i)->endOfMonth(),
                    'content' => "This is a detailed report for {$student->first_name}. The student has shown great progress in coding fundamentals. They have completed several projects including a basic calculator and a simple game. Attendance has been excellent and the student is engaged during classes.",
                    'summary' => "Excellent progress with strong engagement and project completion.",
                    'rating' => rand(7, 10),
                    'status' => $status,
                    'submitted_at' => $status !== 'draft' ? now()->subMonths($i)->addDays(5) : null,
                    'created_by' => $tutorUser->id,
                ]);

                // Add comments to submitted reports
                if ($status !== 'draft') {
                    // Tutor's own comment
                    TutorReportComment::create([
                        'report_id' => $report->id,
                        'user_id' => $tutorUser->id,
                        'comment' => 'I am very pleased with the progress shown by this student.',
                        'role' => 'tutor',
                    ]);

                    // Manager comment if status is manager_review
                    if ($status === 'manager_review' && $managers->isNotEmpty()) {
                        $manager = $managers->first();
                        TutorReportComment::create([
                            'report_id' => $report->id,
                            'user_id' => $manager->id,
                            'comment' => 'Good report. The assessment is thorough and well-documented.',
                            'role' => 'manager',
                        ]);
                    }
                }
            }

            // Create availability schedule for each tutor (Monday to Friday)
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $timeSlots = [
                ['start' => '09:00:00', 'end' => '12:00:00'],
                ['start' => '14:00:00', 'end' => '17:00:00'],
                ['start' => '10:00:00', 'end' => '13:00:00'],
            ];

            foreach ($days as $day) {
                $slot = $timeSlots[array_rand($timeSlots)];

                TutorAvailability::create([
                    'tutor_id' => $tutor->id,
                    'day' => $day,
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    'notes' => 'Available for classes',
                    'is_active' => true,
                ]);
            }

            // Create notifications for each tutor
            $notifications = [
                [
                    'title' => 'New Class Assignment',
                    'body' => 'You have been assigned to a new class starting next week.',
                    'type' => 'schedule',
                    'is_read' => false,
                ],
                [
                    'title' => 'Payment Processed',
                    'body' => 'Your payment for this month has been processed successfully.',
                    'type' => 'payment',
                    'is_read' => true,
                ],
                [
                    'title' => 'Monthly Report Due',
                    'body' => 'Please submit your monthly report by the end of this week.',
                    'type' => 'alert',
                    'is_read' => false,
                ],
                [
                    'title' => 'System Maintenance',
                    'body' => 'The portal will undergo maintenance on Sunday from 2 AM to 4 AM.',
                    'type' => 'system',
                    'is_read' => true,
                ],
            ];

            foreach ($notifications as $notification) {
                TutorNotification::create([
                    'tutor_id' => $tutor->id,
                    'title' => $notification['title'],
                    'body' => $notification['body'],
                    'type' => $notification['type'],
                    'is_read' => $notification['is_read'],
                    'meta' => json_encode(['source' => 'system']),
                ]);
            }
        }

        $this->command->info('Tutor reports, availabilities, and notifications seeded successfully!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ParentNotification;

class ParentNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with parent role
        $parents = User::whereHas('roles', function ($query) {
            $query->where('name', 'parent');
        })->get();

        if ($parents->isEmpty()) {
            $this->command->warn('⚠ No parent users found. Please run ParentPortalSeeder first.');
            return;
        }

        $count = 0;

        foreach ($parents as $parent) {
            // Get the parent's students
            $students = $parent->guardiansOf;

            if ($students->isEmpty()) {
                continue;
            }

            $studentNames = $students->pluck('first_name')->toArray();
            $primaryStudent = $students->first();

            // Sample notification templates
            $notifications = [
                [
                    'type' => 'report_ready',
                    'data' => [
                        'title' => 'New Progress Report Available',
                        'message' => "A new progress report for {$primaryStudent->first_name} is now available for review.",
                        'student_id' => $primaryStudent->id,
                        'student_name' => $primaryStudent->fullName(),
                        'report_date' => now()->subDays(2)->format('Y-m-d'),
                    ],
                    'read_at' => null, // Unread
                ],
                [
                    'type' => 'attendance_alert',
                    'data' => [
                        'title' => 'Attendance Reminder',
                        'message' => "Reminder: {$primaryStudent->first_name} has a class scheduled for tomorrow at 3:00 PM.",
                        'student_id' => $primaryStudent->id,
                        'student_name' => $primaryStudent->fullName(),
                        'class_date' => now()->addDay()->format('Y-m-d'),
                        'class_time' => '3:00 PM',
                    ],
                    'read_at' => now()->subDays(1), // Read
                ],
                [
                    'type' => 'milestone_completed',
                    'data' => [
                        'title' => 'Milestone Achievement',
                        'message' => "{$primaryStudent->first_name} has completed a new milestone: 'Build a Simple Animation'! Great job!",
                        'student_id' => $primaryStudent->id,
                        'student_name' => $primaryStudent->fullName(),
                        'milestone' => 'Build a Simple Animation',
                        'points_earned' => 20,
                    ],
                    'read_at' => null, // Unread
                ],
            ];

            foreach ($notifications as $notification) {
                // Check if similar notification already exists
                $exists = ParentNotification::where('parent_id', $parent->id)
                    ->where('type', $notification['type'])
                    ->whereJsonContains('data->student_id', $notification['data']['student_id'])
                    ->exists();

                if (!$exists) {
                    ParentNotification::create([
                        'parent_id' => $parent->id,
                        'type' => $notification['type'],
                        'data' => $notification['data'],
                        'read_at' => $notification['read_at'],
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info("✓ Parent notification seeder completed: Created {$count} notifications for {$parents->count()} parents");
    }
}

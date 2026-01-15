<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorReport;
use App\Models\Report;
use App\Models\TutorAssessment;
use App\Models\AttendanceRecord;
use App\Models\Notice;
use App\Models\ParentNotification;
use App\Models\TutorNotification;
use App\Models\AdminNotification;
use App\Models\DirectorNotification;
use App\Models\ManagerNotification;
use App\Models\Message;
use App\Models\Payment;
use App\Models\TutorReportComment;
use App\Models\AssessmentRating;
use App\Models\DirectorAction;
use App\Models\PenaltyTransaction;
use App\Models\StudentCourseProgress;
use App\Models\StudentProgress;
use App\Models\TutorAvailability;
use App\Models\TutorTodo;
use App\Models\MonthlyClassSchedule;
use App\Models\ActivityLog;

class CleanSlateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:clean-slate {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all tutors, students, parents, reports, attendance, assessments, notices, and notifications while keeping manager, admin, and director accounts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  WARNING: This will DELETE ALL of the following:');
            $this->line('   - All Students');
            $this->line('   - All Tutors');
            $this->line('   - All Parents');
            $this->line('   - All Reports');
            $this->line('   - All Attendance Records');
            $this->line('   - All Assessments');
            $this->line('   - All Notices');
            $this->line('   - All Notifications');
            $this->line('   - All related records (payments, messages, schedules, etc.)');
            $this->newLine();
            $this->info('✓ The following will be PRESERVED:');
            $this->line('   - Manager accounts');
            $this->line('   - Admin accounts');
            $this->line('   - Director accounts');
            $this->newLine();

            if (!$this->confirm('Are you absolutely sure you want to proceed?')) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }

            if (!$this->confirm('This action cannot be undone. Are you REALLY sure?')) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info('Starting clean slate operation...');
        $this->newLine();

        DB::beginTransaction();

        try {
            // Get IDs of users to delete (parents, students, tutors)
            $parentUserIds = User::whereHas('roles', function ($query) {
                $query->where('name', 'parent');
            })->pluck('id')->toArray();

            $studentUserIds = User::whereHas('roles', function ($query) {
                $query->where('name', 'student');
            })->pluck('id')->toArray();

            $tutorUserIds = User::whereHas('roles', function ($query) {
                $query->where('name', 'tutor');
            })->pluck('id')->toArray();

            $userIdsToDelete = array_merge($parentUserIds, $studentUserIds, $tutorUserIds);

            $this->info('Found:');
            $this->line('  - ' . count($parentUserIds) . ' parent users');
            $this->line('  - ' . count($studentUserIds) . ' student users');
            $this->line('  - ' . count($tutorUserIds) . ' tutor users');
            $this->newLine();

            // Step 1: Delete Messages
            $this->task('Deleting Messages', function () use ($userIdsToDelete) {
                return Message::where(function ($query) use ($userIdsToDelete) {
                    $query->whereIn('sender_id', $userIdsToDelete)
                          ->orWhereIn('recipient_id', $userIdsToDelete)
                          ->orWhereNotNull('student_id');
                })->delete();
            });

            // Step 2: Delete TutorReportComments
            $this->task('Deleting Tutor Report Comments', function () {
                return TutorReportComment::whereHas('report')->delete();
            });

            // Step 3: Delete AssessmentRatings
            $this->task('Deleting Assessment Ratings', function () {
                return AssessmentRating::whereHas('assessment')->delete();
            });

            // Step 4: Delete PenaltyTransactions
            $this->task('Deleting Penalty Transactions', function () {
                return PenaltyTransaction::query()->delete();
            });

            // Step 5: Delete DirectorActions
            $this->task('Deleting Director Actions', function () {
                return DirectorAction::query()->delete();
            });

            // Step 6: Delete TutorAssessments
            $this->task('Deleting Tutor Assessments', function () {
                return TutorAssessment::query()->delete();
            });

            // Step 7: Delete TutorReports
            $this->task('Deleting Tutor Reports', function () {
                return TutorReport::query()->delete();
            });

            // Step 8: Delete Reports
            $this->task('Deleting Reports', function () {
                return Report::query()->delete();
            });

            // Step 9: Delete AttendanceRecords
            $this->task('Deleting Attendance Records', function () {
                return AttendanceRecord::query()->delete();
            });

            // Step 10: Delete Payments
            $this->task('Deleting Payments', function () {
                return Payment::query()->delete();
            });

            // Step 11: Delete StudentCourseProgress
            $this->task('Deleting Student Course Progress', function () {
                return StudentCourseProgress::query()->delete();
            });

            // Step 12: Delete StudentProgress
            $this->task('Deleting Student Progress', function () {
                return StudentProgress::query()->delete();
            });

            // Step 13: Delete StudentPortalSettings
            $this->task('Deleting Student Portal Settings', function () {
                return DB::table('student_portal_settings')->delete();
            });

            // Step 14: Delete MonthlyClassSchedules
            $this->task('Deleting Monthly Class Schedules', function () {
                return MonthlyClassSchedule::query()->delete();
            });

            // Step 15: Delete TutorAvailabilities
            $this->task('Deleting Tutor Availabilities', function () {
                return TutorAvailability::query()->delete();
            });

            // Step 16: Delete TutorTodos
            $this->task('Deleting Tutor Todos', function () {
                return TutorTodo::query()->delete();
            });

            // Step 17: Delete guardian_student pivot records
            $this->task('Deleting Guardian-Student Relationships', function () {
                return DB::table('guardian_student')->delete();
            });

            // Step 18: Delete Students (force delete to remove soft deleted as well)
            $this->task('Deleting Students', function () {
                Student::withTrashed()->forceDelete();
                return true;
            });

            // Step 19: Delete Tutors (force delete to remove soft deleted as well)
            $this->task('Deleting Tutors', function () {
                Tutor::withTrashed()->forceDelete();
                return true;
            });

            // Step 20: Delete Notices
            $this->task('Deleting Notices', function () {
                return Notice::query()->delete();
            });

            // Step 21: Delete All Notifications
            $this->task('Deleting Parent Notifications', function () {
                return ParentNotification::query()->delete();
            });

            $this->task('Deleting Tutor Notifications', function () {
                return TutorNotification::query()->delete();
            });

            $this->task('Deleting Admin Notifications', function () {
                return AdminNotification::query()->delete();
            });

            $this->task('Deleting Director Notifications', function () {
                return DirectorNotification::query()->delete();
            });

            $this->task('Deleting Manager Notifications', function () {
                return ManagerNotification::query()->delete();
            });

            // Step 22: Delete Activity Logs related to deleted users
            $this->task('Cleaning Activity Logs', function () use ($userIdsToDelete) {
                return ActivityLog::whereIn('user_id', $userIdsToDelete)->delete();
            });

            // Step 23: Delete Audit Logs for deleted models
            $this->task('Cleaning Audit Logs', function () {
                return DB::table('audit_logs')
                    ->whereIn('auditable_type', [
                        'App\\Models\\Student',
                        'App\\Models\\Tutor',
                        'App\\Models\\TutorReport',
                        'App\\Models\\TutorAssessment',
                        'App\\Models\\AttendanceRecord',
                    ])
                    ->delete();
            });

            // Step 24: Delete role_user pivot for deleted users
            $this->task('Cleaning User Role Assignments', function () use ($userIdsToDelete) {
                return DB::table('role_user')->whereIn('user_id', $userIdsToDelete)->delete();
            });

            // Step 25: Delete User accounts (parent, student, tutor roles)
            $this->task('Deleting Parent/Student/Tutor User Accounts', function () use ($userIdsToDelete) {
                return User::whereIn('id', $userIdsToDelete)->delete();
            });

            DB::commit();

            $this->newLine();
            $this->info('✅ Clean slate operation completed successfully!');
            $this->newLine();

            // Show preserved accounts
            $managerCount = User::whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            })->count();

            $adminCount = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();

            $directorCount = User::whereHas('roles', function ($query) {
                $query->where('name', 'director');
            })->count();

            $this->info('Preserved Accounts:');
            $this->line('  - ' . $managerCount . ' Manager(s)');
            $this->line('  - ' . $adminCount . ' Admin(s)');
            $this->line('  - ' . $directorCount . ' Director(s)');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error during clean slate operation: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}

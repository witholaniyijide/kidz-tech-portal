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
            // Get IDs of users to delete (parents, tutors)
            $parentUserIds = User::whereHas('roles', function ($query) {
                $query->where('name', 'parent');
            })->pluck('id')->toArray();

            $tutorUserIds = User::whereHas('roles', function ($query) {
                $query->where('name', 'tutor');
            })->pluck('id')->toArray();

            $userIdsToDelete = array_merge($parentUserIds, $tutorUserIds);

            // Count students from students table (they don't have user accounts)
            $studentCount = Student::withTrashed()->count();
            $tutorCount = Tutor::withTrashed()->count();

            $this->info('Found:');
            $this->line('  - ' . count($parentUserIds) . ' parent users');
            $this->line('  - ' . $studentCount . ' students (in students table)');
            $this->line('  - ' . $tutorCount . ' tutors (' . count($tutorUserIds) . ' with user accounts)');
            $this->newLine();

            // Step 1: Delete Messages
            $this->info('Deleting Messages...');
            $count = Message::where(function ($query) use ($userIdsToDelete) {
                $query->whereIn('sender_id', $userIdsToDelete)
                      ->orWhereIn('recipient_id', $userIdsToDelete)
                      ->orWhereNotNull('student_id');
            })->delete();
            $this->line('  ✓ Deleted ' . $count . ' messages');

            // Step 2: Delete TutorReportComments
            $this->info('Deleting Tutor Report Comments...');
            $count = TutorReportComment::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' comments');

            // Step 3: Delete AssessmentRatings
            $this->info('Deleting Assessment Ratings...');
            $count = AssessmentRating::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' ratings');

            // Step 4: Delete PenaltyTransactions
            $this->info('Deleting Penalty Transactions...');
            $count = PenaltyTransaction::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' transactions');

            // Step 5: Delete DirectorActions
            $this->info('Deleting Director Actions...');
            $count = DirectorAction::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' actions');

            // Step 6: Delete TutorAssessments
            $this->info('Deleting Tutor Assessments...');
            $count = TutorAssessment::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' assessments');

            // Step 7: Delete TutorReports
            $this->info('Deleting Tutor Reports...');
            $count = TutorReport::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' reports');

            // Step 8: Delete Reports
            $this->info('Deleting Reports...');
            $count = Report::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' reports');

            // Step 9: Delete AttendanceRecords
            $this->info('Deleting Attendance Records...');
            $count = AttendanceRecord::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' records');

            // Step 10: Delete Payments
            $this->info('Deleting Payments...');
            $count = Payment::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' payments');

            // Step 11: Delete StudentCourseProgress
            $this->info('Deleting Student Course Progress...');
            $count = StudentCourseProgress::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' progress records');

            // Step 12: Delete StudentProgress
            $this->info('Deleting Student Progress...');
            $count = StudentProgress::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' progress records');

            // Step 13: Delete StudentPortalSettings
            $this->info('Deleting Student Portal Settings...');
            $count = DB::table('student_portal_settings')->delete();
            $this->line('  ✓ Deleted ' . $count . ' settings');

            // Step 14: Delete MonthlyClassSchedules
            $this->info('Deleting Monthly Class Schedules...');
            $count = MonthlyClassSchedule::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' schedules');

            // Step 15: Delete TutorAvailabilities
            $this->info('Deleting Tutor Availabilities...');
            $count = TutorAvailability::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' availabilities');

            // Step 16: Delete TutorTodos
            $this->info('Deleting Tutor Todos...');
            $count = TutorTodo::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' todos');

            // Step 17: Delete guardian_student pivot records
            $this->info('Deleting Guardian-Student Relationships...');
            $count = DB::table('guardian_student')->delete();
            $this->line('  ✓ Deleted ' . $count . ' relationships');

            // Step 18: Delete Students (force delete to remove soft deleted as well)
            $this->info('Deleting Students...');
            Student::withTrashed()->forceDelete();
            $this->line('  ✓ Deleted ' . $studentCount . ' students');

            // Step 19: Delete Tutors (force delete to remove soft deleted as well)
            $this->info('Deleting Tutors...');
            Tutor::withTrashed()->forceDelete();
            $this->line('  ✓ Deleted ' . $tutorCount . ' tutors');

            // Step 20: Delete Notices
            $this->info('Deleting Notices...');
            $count = Notice::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' notices');

            // Step 21: Delete All Notifications
            $this->info('Deleting Parent Notifications...');
            $count = ParentNotification::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' notifications');

            $this->info('Deleting Tutor Notifications...');
            $count = TutorNotification::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' notifications');

            $this->info('Deleting Admin Notifications...');
            $count = AdminNotification::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' notifications');

            $this->info('Deleting Director Notifications...');
            $count = DirectorNotification::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' notifications');

            $this->info('Deleting Manager Notifications...');
            $count = ManagerNotification::query()->delete();
            $this->line('  ✓ Deleted ' . $count . ' notifications');

            // Step 22: Delete Activity Logs related to deleted users
            $this->info('Cleaning Activity Logs...');
            $count = ActivityLog::whereIn('user_id', $userIdsToDelete)->delete();
            $this->line('  ✓ Deleted ' . $count . ' logs');

            // Step 23: Delete Audit Logs for deleted models
            $this->info('Cleaning Audit Logs...');
            $count = DB::table('audit_logs')
                ->whereIn('auditable_type', [
                    'App\\Models\\Student',
                    'App\\Models\\Tutor',
                    'App\\Models\\TutorReport',
                    'App\\Models\\TutorAssessment',
                    'App\\Models\\AttendanceRecord',
                ])
                ->delete();
            $this->line('  ✓ Deleted ' . $count . ' logs');

            // Step 24: Delete role_user pivot for deleted users
            $this->info('Cleaning User Role Assignments...');
            $count = DB::table('role_user')->whereIn('user_id', $userIdsToDelete)->delete();
            $this->line('  ✓ Deleted ' . $count . ' role assignments');

            // Step 25: Delete User accounts (parent, tutor roles)
            $this->info('Deleting Parent/Tutor User Accounts...');
            $count = User::whereIn('id', $userIdsToDelete)->delete();
            $this->line('  ✓ Deleted ' . $count . ' user accounts');

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

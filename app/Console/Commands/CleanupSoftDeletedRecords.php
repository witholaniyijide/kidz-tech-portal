<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tutor;
use App\Models\Student;

class CleanupSoftDeletedRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:cleanup-soft-deleted {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete all soft-deleted tutors and students to free up emails for reuse';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Count soft-deleted records
        $softDeletedTutors = Tutor::onlyTrashed()->count();
        $softDeletedStudents = Student::onlyTrashed()->count();

        if ($softDeletedTutors === 0 && $softDeletedStudents === 0) {
            $this->info('✓ No soft-deleted records found. Database is clean.');
            return Command::SUCCESS;
        }

        $this->warn('⚠️  WARNING: This will PERMANENTLY delete:');
        $this->line('   - ' . $softDeletedTutors . ' soft-deleted tutor(s)');
        $this->line('   - ' . $softDeletedStudents . ' soft-deleted student(s)');
        $this->newLine();
        $this->info('This will free up their emails for reuse.');
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to proceed?')) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        // Force delete soft-deleted tutors
        if ($softDeletedTutors > 0) {
            $this->info('Permanently deleting ' . $softDeletedTutors . ' tutor(s)...');

            // Get soft-deleted tutors with their user accounts
            $tutors = Tutor::onlyTrashed()->with('user')->get();

            foreach ($tutors as $tutor) {
                $email = $tutor->email;
                $user = $tutor->user;

                // Force delete tutor
                $tutor->forceDelete();

                // Delete associated user account
                if ($user) {
                    $user->delete();
                }

                $this->line('  ✓ Deleted tutor: ' . $email);
            }
        }

        // Force delete soft-deleted students
        if ($softDeletedStudents > 0) {
            $this->info('Permanently deleting ' . $softDeletedStudents . ' student(s)...');

            $students = Student::onlyTrashed()->get();

            foreach ($students as $student) {
                $email = $student->email ?? 'N/A';

                // Force delete student
                $student->forceDelete();

                $this->line('  ✓ Deleted student: ' . $email);
            }
        }

        $this->newLine();
        $this->info('✅ Cleanup completed successfully!');
        $this->info('All emails from deleted records can now be reused.');

        return Command::SUCCESS;
    }
}

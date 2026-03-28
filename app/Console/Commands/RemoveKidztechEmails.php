<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tutor;

class RemoveKidztechEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:remove-kidztech-emails {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all users and tutors with emails ending in @kidztech.com';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Find all affected records
        $usersWithKidztechEmail = User::where('email', 'like', '%@kidztech.com')->get();
        $tutorsWithKidztechEmail = Tutor::where('email', 'like', '%@kidztech.com')->get();

        if ($usersWithKidztechEmail->isEmpty() && $tutorsWithKidztechEmail->isEmpty()) {
            $this->info('No users or tutors found with @kidztech.com emails.');
            return Command::SUCCESS;
        }

        $this->info('Found the following @kidztech.com emails:');
        $this->newLine();

        if ($usersWithKidztechEmail->isNotEmpty()) {
            $this->info('Users:');
            foreach ($usersWithKidztechEmail as $user) {
                $roles = $user->getRoleNames()->implode(', ');
                $this->line("  - {$user->email} (ID: {$user->id}, Roles: {$roles})");
            }
            $this->newLine();
        }

        if ($tutorsWithKidztechEmail->isNotEmpty()) {
            $this->info('Tutors:');
            foreach ($tutorsWithKidztechEmail as $tutor) {
                $this->line("  - {$tutor->email} (ID: {$tutor->id}, Name: {$tutor->first_name} {$tutor->last_name})");
            }
            $this->newLine();
        }

        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to remove all these records?')) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        DB::beginTransaction();

        try {
            $deletedUsers = 0;
            $deletedTutors = 0;

            // Delete users with @kidztech.com emails
            foreach ($usersWithKidztechEmail as $user) {
                // Remove role assignments first
                DB::table('role_user')->where('user_id', $user->id)->delete();

                // Delete related notifications
                DB::table('manager_notifications')->where('user_id', $user->id)->delete();
                DB::table('director_notifications')->where('user_id', $user->id)->delete();
                DB::table('parent_notifications')->where('parent_id', $user->id)->delete();

                // Delete activity logs
                DB::table('activity_logs')->where('user_id', $user->id)->delete();

                // Delete the user
                $user->delete();
                $deletedUsers++;
            }

            // Update/clear tutors with @kidztech.com emails (set email to null or delete)
            foreach ($tutorsWithKidztechEmail as $tutor) {
                // Delete tutor notifications
                DB::table('tutor_notifications')->where('tutor_id', $tutor->id)->delete();

                // Clear the email instead of deleting the tutor record
                // This preserves historical data while stopping notifications
                $tutor->email = null;
                $tutor->save();
                $deletedTutors++;
            }

            DB::commit();

            $this->newLine();
            $this->info("Successfully processed @kidztech.com emails:");
            $this->line("  - Deleted {$deletedUsers} user(s)");
            $this->line("  - Cleared email for {$deletedTutors} tutor(s)");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

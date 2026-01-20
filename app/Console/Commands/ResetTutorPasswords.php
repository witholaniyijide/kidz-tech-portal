<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetTutorPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tutors:reset-passwords
                            {--password=password123 : The password to set for all tutors}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all tutor user account passwords to a default password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = $this->option('password');

        // Get all tutors
        $tutorRole = Role::where('name', 'tutor')->first();

        if (!$tutorRole) {
            $this->error('Tutor role not found in the database.');
            return 1;
        }

        $tutorUsers = $tutorRole->users;

        if ($tutorUsers->isEmpty()) {
            $this->info('No tutor users found.');
            return 0;
        }

        $this->info("Found {$tutorUsers->count()} tutor user accounts.");

        // Show confirmation unless --force is used
        if (!$this->option('force')) {
            if (!$this->confirm("Do you want to reset all {$tutorUsers->count()} tutor passwords to '{$password}'?")) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Resetting passwords...');

        $bar = $this->output->createProgressBar($tutorUsers->count());
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($tutorUsers as $user) {
            try {
                $user->password = Hash::make($password);
                $user->password_change_required = true;
                $user->save();
                $successCount++;
            } catch (\Exception $e) {
                $this->error("\nFailed to update user {$user->email}: " . $e->getMessage());
                $failCount++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Password reset completed!");
        $this->info("Successfully updated: {$successCount} accounts");
        if ($failCount > 0) {
            $this->warn("Failed to update: {$failCount} accounts");
        }
        $this->info("Default password is now: {$password}");
        $this->warn("All tutors will be required to change their password on first login.");

        return 0;
    }
}

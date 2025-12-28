<?php

namespace App\Services;

use App\Mail\ParentAccountWelcomeMail;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ParentAccountService
{
    protected const DEFAULT_PASSWORD = 'password';

    /**
     * Create parent accounts for a student based on father and mother information.
     *
     * @param Student $student The student to create parent accounts for
     * @return array Array of created/linked parent user IDs
     */
    public function createParentAccountsForStudent(Student $student): array
    {
        $parentUserIds = [];

        // Handle father account
        if (!empty($student->father_email)) {
            $fatherUser = $this->createOrLinkParent(
                email: $student->father_email,
                name: $student->father_name ?? 'Parent',
                phone: $student->father_phone,
                student: $student,
                relationship: 'father',
                isPrimaryContact: true
            );
            if ($fatherUser) {
                $parentUserIds['father'] = $fatherUser->id;
            }
        }

        // Handle mother account
        if (!empty($student->mother_email)) {
            $motherUser = $this->createOrLinkParent(
                email: $student->mother_email,
                name: $student->mother_name ?? 'Parent',
                phone: $student->mother_phone,
                student: $student,
                relationship: 'mother',
                isPrimaryContact: empty($student->father_email) // Primary if no father email
            );
            if ($motherUser) {
                $parentUserIds['mother'] = $motherUser->id;
            }
        }

        return $parentUserIds;
    }

    /**
     * Create or link a parent user account.
     *
     * @param string $email Parent's email address
     * @param string $name Parent's name
     * @param string|null $phone Parent's phone number
     * @param Student $student The student to link to
     * @param string $relationship The relationship type (father, mother, guardian)
     * @param bool $isPrimaryContact Whether this is the primary contact
     * @return User|null The created or existing user, or null on failure
     */
    protected function createOrLinkParent(
        string $email,
        string $name,
        ?string $phone,
        Student $student,
        string $relationship,
        bool $isPrimaryContact = false
    ): ?User {
        try {
            // Check if user already exists with this email
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                // User exists - just link to the student if not already linked
                $this->linkParentToStudent($existingUser, $student, $relationship, $isPrimaryContact);

                Log::info("Linked existing parent account to student", [
                    'user_id' => $existingUser->id,
                    'student_id' => $student->id,
                    'relationship' => $relationship
                ]);

                return $existingUser;
            }

            // Create new parent user account
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'password_change_required' => true,
            ]);

            // Assign parent role
            $this->assignParentRole($user);

            // Link to student
            $this->linkParentToStudent($user, $student, $relationship, $isPrimaryContact);

            // Send welcome email
            $this->sendWelcomeEmail($user, $student);

            Log::info("Created new parent account", [
                'user_id' => $user->id,
                'email' => $email,
                'student_id' => $student->id,
                'relationship' => $relationship
            ]);

            return $user;

        } catch (\Exception $e) {
            Log::error("Failed to create/link parent account", [
                'email' => $email,
                'student_id' => $student->id,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Assign the parent role to a user.
     */
    protected function assignParentRole(User $user): void
    {
        $parentRole = Role::where('name', 'parent')->first();

        if ($parentRole && !$user->roles->contains($parentRole->id)) {
            $user->roles()->attach($parentRole->id);
        }
    }

    /**
     * Link a parent user to a student via the guardian_student pivot table.
     */
    protected function linkParentToStudent(
        User $user,
        Student $student,
        string $relationship,
        bool $isPrimaryContact = false
    ): void {
        // Check if the link already exists
        $existingLink = DB::table('guardian_student')
            ->where('user_id', $user->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingLink) {
            // Update existing link
            DB::table('guardian_student')
                ->where('user_id', $user->id)
                ->where('student_id', $student->id)
                ->update([
                    'relationship' => $relationship,
                    'primary_contact' => $isPrimaryContact,
                    'updated_at' => now(),
                ]);
        } else {
            // Create new link
            $student->guardians()->attach($user->id, [
                'relationship' => $relationship,
                'primary_contact' => $isPrimaryContact,
            ]);
        }

        // Also update the parent_id on the student if this is primary contact
        if ($isPrimaryContact) {
            $student->update(['parent_id' => $user->id]);
        }
    }

    /**
     * Send welcome email to the parent with login credentials.
     */
    protected function sendWelcomeEmail(User $user, Student $student): void
    {
        try {
            if (empty($user->email)) {
                Log::warning("Cannot send welcome email - no email address", [
                    'user_id' => $user->id
                ]);
                return;
            }

            $loginUrl = config('app.url') . '/login';

            Mail::to($user->email)->send(new ParentAccountWelcomeMail(
                parent: $user,
                student: $student,
                password: self::DEFAULT_PASSWORD,
                loginUrl: $loginUrl
            ));

            Log::info("Sent parent welcome email", [
                'user_id' => $user->id,
                'email' => $user->email,
                'student_id' => $student->id
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to send parent welcome email", [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            // Don't throw - email failure shouldn't break account creation
        }
    }
}

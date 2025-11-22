<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            // Keep existing: first_name, last_name, email, phone, bio
            if (!Schema::hasColumn('tutors', 'occupation')) {
                $table->string('occupation')->nullable();
            }
            if (!Schema::hasColumn('tutors', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('tutors', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }

            // Emergency Contact
            if (!Schema::hasColumn('tutors', 'contact_person_name')) {
                $table->string('contact_person_name')->nullable();
            }
            if (!Schema::hasColumn('tutors', 'contact_person_relationship')) {
                $table->string('contact_person_relationship')->nullable();
            }
            if (!Schema::hasColumn('tutors', 'contact_person_phone')) {
                $table->string('contact_person_phone')->nullable();
            }

            // Payment Info
            if (!Schema::hasColumn('tutors', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (!Schema::hasColumn('tutors', 'account_number')) {
                $table->string('account_number')->nullable();
            }
            if (!Schema::hasColumn('tutors', 'account_name')) {
                $table->string('account_name')->nullable();
            }

            // Profile
            if (!Schema::hasColumn('tutors', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->comment('Path to profile photo');
            }
        });

        // Drop specializations column if it exists
        if (Schema::hasColumn('tutors', 'specializations')) {
            Schema::table('tutors', function (Blueprint $table) {
                $table->dropColumn('specializations');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $columns = [
                'occupation', 'location', 'date_of_birth',
                'contact_person_name', 'contact_person_relationship', 'contact_person_phone',
                'bank_name', 'account_number', 'account_name', 'profile_photo'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tutors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

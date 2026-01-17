<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL for ENUM column to avoid Doctrine issues
        DB::statement("ALTER TABLE tutors MODIFY COLUMN date_of_birth DATE NULL");
        DB::statement("ALTER TABLE tutors MODIFY COLUMN hire_date DATE NULL");
        DB::statement("ALTER TABLE tutors MODIFY COLUMN gender ENUM('male', 'female') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL for ENUM column to avoid Doctrine issues
        DB::statement("ALTER TABLE tutors MODIFY COLUMN date_of_birth DATE NOT NULL");
        DB::statement("ALTER TABLE tutors MODIFY COLUMN hire_date DATE NOT NULL");
        DB::statement("ALTER TABLE tutors MODIFY COLUMN gender ENUM('male', 'female') NOT NULL");
    }
};

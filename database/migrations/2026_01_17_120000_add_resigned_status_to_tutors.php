<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'resigned' to the status ENUM
        DB::statement("ALTER TABLE tutors MODIFY COLUMN status ENUM('active', 'inactive', 'on_leave', 'resigned') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'resigned' from the status ENUM
        DB::statement("ALTER TABLE tutors MODIFY COLUMN status ENUM('active', 'inactive', 'on_leave') DEFAULT 'active'");
    }
};

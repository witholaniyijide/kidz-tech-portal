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
        // Add 'rejected' to the status enum
        DB::statement("ALTER TABLE tutor_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'approved-by-manager', 'approved-by-director', 'rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'rejected' from the status enum
        DB::statement("ALTER TABLE tutor_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'approved-by-manager', 'approved-by-director') DEFAULT 'draft'");
    }
};

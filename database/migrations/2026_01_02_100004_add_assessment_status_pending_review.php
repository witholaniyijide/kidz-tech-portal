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
        // Modify status enum to include 'pending_review'
        DB::statement("ALTER TABLE tutor_assessments MODIFY COLUMN status ENUM('draft', 'pending_review', 'submitted', 'approved-by-manager', 'approved-by-director') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tutor_assessments MODIFY COLUMN status ENUM('draft', 'submitted', 'approved-by-manager', 'approved-by-director') DEFAULT 'draft'");
    }
};

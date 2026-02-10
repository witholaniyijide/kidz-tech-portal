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
        Schema::table('tutor_assessments', function (Blueprint $table) {
            // Stand-in assessment fields
            if (!Schema::hasColumn('tutor_assessments', 'is_stand_in')) {
                $table->boolean('is_stand_in')->default(false)->after('status');
            }
            if (!Schema::hasColumn('tutor_assessments', 'original_tutor_id')) {
                // The original assigned tutor (student's regular tutor)
                $table->foreignId('original_tutor_id')->nullable()->after('is_stand_in')
                    ->constrained('tutors')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_assessments', function (Blueprint $table) {
            if (Schema::hasColumn('tutor_assessments', 'original_tutor_id')) {
                $table->dropForeign(['original_tutor_id']);
                $table->dropColumn('original_tutor_id');
            }
            if (Schema::hasColumn('tutor_assessments', 'is_stand_in')) {
                $table->dropColumn('is_stand_in');
            }
        });
    }
};

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
            if (!Schema::hasColumn('tutors', 'specialization')) {
                $table->string('specialization')->nullable()->after('hourly_rate');
            }
            if (!Schema::hasColumn('tutors', 'qualifications')) {
                $table->text('qualifications')->nullable()->after('specialization');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            if (Schema::hasColumn('tutors', 'specialization')) {
                $table->dropColumn('specialization');
            }
            if (Schema::hasColumn('tutors', 'qualifications')) {
                $table->dropColumn('qualifications');
            }
        });
    }
};

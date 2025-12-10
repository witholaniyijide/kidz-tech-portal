<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->integer('reports_count')->default(0)->after('status');
            $table->timestamp('last_reported_at')->nullable()->after('reports_count');
        });
    }

    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->dropColumn(['reports_count', 'last_reported_at']);
        });
    }
};

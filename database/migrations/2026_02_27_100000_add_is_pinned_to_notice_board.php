<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notice_board', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('status');
            $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            $table->unsignedBigInteger('pinned_by')->nullable()->after('pinned_at');
        });
    }

    public function down(): void
    {
        Schema::table('notice_board', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'pinned_at', 'pinned_by']);
        });
    }
};

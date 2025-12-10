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
        Schema::table('users', function (Blueprint $table) {
            $table->string('push_token')->nullable()->after('remember_token');
            $table->string('device_type')->nullable()->after('push_token'); // 'ios', 'android', 'web'
            $table->timestamp('push_token_updated_at')->nullable()->after('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['push_token', 'device_type', 'push_token_updated_at']);
        });
    }
};

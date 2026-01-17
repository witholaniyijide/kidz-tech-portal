<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * STUDENTS TABLE
         */
        Schema::table('students', function (Blueprint $table) {
            if (!$this->indexExists('students', ['parent_id'])) {
                $table->index('parent_id');
            }
            if (!$this->indexExists('students', ['tutor_id'])) {
                $table->index('tutor_id');
            }
            if (!$this->indexExists('students', ['status', 'created_at'])) {
                $table->index(['status', 'created_at']);
            }
        });

        /**
         * REPORTS TABLE (Monthly performance reports)
         */
        Schema::table('reports', function (Blueprint $table) {
            if (!$this->indexExists('reports', ['student_id'])) {
                $table->index('student_id');
            }
            if (!$this->indexExists('reports', ['instructor_id'])) {
                $table->index('instructor_id');
            }
            if (!$this->indexExists('reports', ['approved_by'])) {
                $table->index('approved_by');
            }
            if (!$this->indexExists('reports', ['status'])) {
                $table->index('status');
            }
            if (!$this->indexExists('reports', ['created_at'])) {
                $table->index('created_at');
            }
        });

        /**
         * TUTOR REPORTS TABLE (Tutor → Manager → Director workflow)
         */
        Schema::table('tutor_reports', function (Blueprint $table) {
            if (!$this->indexExists('tutor_reports', ['tutor_id'])) {
                $table->index('tutor_id');
            }
            if (!$this->indexExists('tutor_reports', ['student_id'])) {
                $table->index('student_id');
            }
            if (!$this->indexExists('tutor_reports', ['director_id'])) {
                $table->index('director_id');
            }
            if (!$this->indexExists('tutor_reports', ['created_by'])) {
                $table->index('created_by');
            }
            if (!$this->indexExists('tutor_reports', ['status'])) {
                $table->index('status');
            }

            // High-impact composite indexes for dashboards
            if (!$this->indexExists('tutor_reports', ['student_id', 'created_at'])) {
                $table->index(['student_id', 'created_at']);
            }
            if (!$this->indexExists('tutor_reports', ['tutor_id', 'created_at'])) {
                $table->index(['tutor_id', 'created_at']);
            }
        });

        /**
         * PAYMENTS TABLE (Financial reporting)
         */
        Schema::table('payments', function (Blueprint $table) {
            if (!$this->indexExists('payments', ['student_id'])) {
                $table->index('student_id');
            }
            if (!$this->indexExists('payments', ['recorded_by'])) {
                $table->index('recorded_by');
            }
            if (!$this->indexExists('payments', ['status'])) {
                $table->index('status');
            }
            if (!$this->indexExists('payments', ['payment_date'])) {
                $table->index('payment_date');
            }
        });

        /**
         * NOTIFICATIONS TABLE (Laravel polymorphic)
         */
        Schema::table('notifications', function (Blueprint $table) {
            if (!$this->indexExists('notifications', ['notifiable_id'])) {
                $table->index('notifiable_id');
            }
            if (!$this->indexExists('notifications', ['created_at'])) {
                $table->index('created_at');
            }
        });
    }

    public function down(): void
    {
        // Down intentionally omitted for safety & non-destructive migration philosophy
    }

    /**
     * Column-based index existence check (DBAL)
     */
    private function indexExists(string $table, array $columns): bool
    {
        $schema = Schema::getConnection()->getDoctrineSchemaManager();
        $indexes = $schema->listTableIndexes($table);

        foreach ($indexes as $index) {
            if ($index->getColumns() === $columns) {
                return true;
            }
        }

        return false;
    }
};

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
        Schema::table('attendances', function (Blueprint $table) {
            // Composite indexes untuk common query patterns
            $table->index(['user_id', 'status', 'date'], 'idx_attendances_user_status_date');
            $table->index(['status', 'date'], 'idx_attendances_status_date');
            $table->index(['date'], 'idx_attendances_date');
            $table->index(['user_id', 'created_at'], 'idx_attendances_user_created');
        });

        Schema::table('complaints', function (Blueprint $table) {
            // Composite indexes untuk quick filtering leave requests
            $table->index(['user_id', 'created_at', 'status'], 'idx_complaints_user_created_status');
            $table->index(['status', 'created_at'], 'idx_complaints_status_created');
            $table->index(['category', 'status'], 'idx_complaints_category_status');
            $table->index(['user_id', 'category'], 'idx_complaints_user_category');
        });

        Schema::table('shift_user', function (Blueprint $table) {
            // Index untuk shift assignment queries
            $table->index(['start_date', 'end_date'], 'idx_shift_user_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('idx_attendances_user_status_date');
            $table->dropIndex('idx_attendances_status_date');
            $table->dropIndex('idx_attendances_date');
            $table->dropIndex('idx_attendances_user_created');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropIndex('idx_complaints_user_created_status');
            $table->dropIndex('idx_complaints_status_created');
            $table->dropIndex('idx_complaints_category_status');
            $table->dropIndex('idx_complaints_user_category');
        });

        Schema::table('shift_user', function (Blueprint $table) {
            $table->dropIndex('idx_shift_user_dates');
        });
    }
};

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
        // Add missing columns to users table
        if (!Schema::hasColumn('users', 'department')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('department')->nullable()->after('shift_id');
                $table->string('cost_center')->nullable()->after('department');
                $table->unsignedBigInteger('manager_id')->nullable()->after('cost_center');
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Add missing columns to attendances table
        if (!Schema::hasColumn('attendances', 'sync_status')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->string('sync_status')->default('synced')->after('approval_status');
                $table->json('device_info')->nullable()->after('sync_status');
            });
        }

        // Add indexes to attendances table for performance
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasIndex('attendances', 'attendances_user_id_date_index')) {
                $table->index(['user_id', 'date']);
            }
            if (!Schema::hasIndex('attendances', 'attendances_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('attendances', 'attendances_created_at_index')) {
                $table->index('created_at');
            }
        });

        // Add indexes to complaints table
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasIndex('complaints', 'complaints_user_id_index')) {
                $table->index('user_id');
            }
            if (!Schema::hasIndex('complaints', 'complaints_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('complaints', 'complaints_created_at_index')) {
                $table->index('created_at');
            }
        });

        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasIndex('users', 'users_email_index')) {
                $table->index('email');
            }
            if (!Schema::hasIndex('users', 'users_employee_id_index')) {
                $table->index('employee_id');
            }
        });

        // Create audit_logs table
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action');
                $table->string('model');
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['user_id', 'created_at']);
                $table->index(['model', 'model_id']);
            });
        }

        // Create api_tokens table
        if (!Schema::hasTable('api_tokens')) {
            Schema::create('api_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('name');
                $table->string('token')->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index('user_id');
            });
        }

        // Create sync_queue table for offline sync
        if (!Schema::hasTable('sync_queue')) {
            Schema::create('sync_queue', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('action'); // create, update, delete
                $table->string('model');
                $table->json('data');
                $table->string('status')->default('pending'); // pending, synced, failed
                $table->text('error_message')->nullable();
                $table->integer('retry_count')->default(0);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'status']);
            });
        }

        // Create notifications table
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'read_at']);
            });
        }

        // Create departments table
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('manager_id')->nullable();
                $table->timestamps();

                $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Create leave_types table
        if (!Schema::hasTable('leave_types')) {
            Schema::create('leave_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->integer('default_quota')->default(0);
                $table->boolean('requires_document')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new tables
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('sync_queue');
        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('audit_logs');

        // Drop columns from existing tables
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['sync_status', 'device_info']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['department', 'cost_center', 'manager_id']);
        });
    }
};

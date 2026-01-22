<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (! Schema::hasColumn('activities', 'evidence_path')) {
                $table->string('evidence_path')->nullable()->after('status');
            }
            if (! Schema::hasColumn('activities', 'signature_path')) {
                $table->string('signature_path')->nullable()->after('evidence_path');
            }
            if (! Schema::hasColumn('activities', 'signature_name')) {
                $table->string('signature_name')->nullable()->after('signature_path');
            }
            if (! Schema::hasColumn('activities', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('signature_name');
            }
            if (! Schema::hasColumn('activities', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('signed_at');
            }
            if (! Schema::hasColumn('activities', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (! Schema::hasColumn('activities', 'location_address')) {
                $table->string('location_address')->nullable()->after('longitude');
            }
            if (! Schema::hasColumn('activities', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (! Schema::hasColumn('activities', 'rejected_reason')) {
                $table->text('rejected_reason')->nullable()->after('approved_at');
            }
        });

        if (Schema::hasColumn('activities', 'evidence') && Schema::hasColumn('activities', 'evidence_path')) {
            DB::table('activities')
                ->whereNull('evidence_path')
                ->whereNotNull('evidence')
                ->update(['evidence_path' => DB::raw('evidence')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $columns = [
                'evidence_path',
                'signature_path',
                'signature_name',
                'signed_at',
                'latitude',
                'longitude',
                'location_address',
                'approved_at',
                'rejected_reason',
            ];

            $existingColumns = array_filter($columns, fn ($column) => Schema::hasColumn('activities', $column));

            if (! empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};

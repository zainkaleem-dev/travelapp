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
        Schema::disableForeignKeyConstraints();
        Schema::table('role_has_permissions', function (Blueprint $table) {
            // Check if column already exists (from failed migration)
            if (!Schema::hasColumn('role_has_permissions', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('role_id');
            }

            // Drop existing primary key safely
            try {
                $table->dropPrimary(['permission_id', 'role_id']);
            } catch (\Exception $e) {
                // Already dropped
            }

            // Instead of PRIMARY, we use UNIQUE because MySQL allows NULL in UNIQUE but not PRIMARY
            $table->unique(['permission_id', 'role_id', 'company_id'], 'role_has_permissions_permission_role_company_unique');

            // Add foreign key
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('role_has_permissions', function (Blueprint $table) {
            // Truncate to avoid isolation issues during rollback
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
            
            try {
                $table->dropForeign(['company_id']);
                $table->dropUnique('role_has_permissions_permission_role_company_unique');
                $table->dropColumn('company_id');
                $table->primary(['permission_id', 'role_id']);
            } catch (\Exception $e) {
                // Handle cases where rollback partially failed
            }
        });
        Schema::enableForeignKeyConstraints();
    }
};

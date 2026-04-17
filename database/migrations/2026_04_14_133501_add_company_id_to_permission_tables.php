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
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('id');
            }
            $table->string('name', 90)->change();
            $table->string('guard_name', 90)->change();

            // Handle the case where the unique index might have been dropped already but the new one failed
            $indexes = DB::getSchemaBuilder()->getIndexes('roles');
            $hasOldUnique = false;
            foreach ($indexes as $index) {
                if ($index['name'] === 'roles_name_guard_name_unique') {
                    $hasOldUnique = true;
                    break;
                }
            }
            if ($hasOldUnique) {
                $table->dropUnique('roles_name_guard_name_unique');
            }
            
            $table->unique(['company_id', 'name', 'guard_name'], 'roles_company_name_guard_unique');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            if (!Schema::hasColumn('model_has_roles', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('role_id');
            }
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('model_has_permissions', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('permission_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Truncate tables to prevent unique constraint violations when dropping company_id
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            \Illuminate\Support\Facades\DB::table('model_has_permissions')->truncate();
            \Illuminate\Support\Facades\DB::table('model_has_roles')->truncate();
            \Illuminate\Support\Facades\DB::table('roles')->truncate();
        } catch (\Exception $e) {}
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('model_has_permissions', function (Blueprint $table) {
            if (Schema::hasColumn('model_has_permissions', 'company_id')) {
                $table->dropColumn('company_id');
            }
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            if (Schema::hasColumn('model_has_roles', 'company_id')) {
                $table->dropColumn('company_id');
            }
        });

        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'company_id')) {
                // Drop index only if it exists
                $indexes = DB::getSchemaBuilder()->getIndexes('roles');
                $hasUnique = false;
                foreach ($indexes as $index) {
                    if ($index['name'] === 'roles_company_name_guard_unique') {
                        $hasUnique = true;
                        break;
                    }
                }
                if ($hasUnique) {
                    $table->dropUnique('roles_company_name_guard_unique');
                }
                
                $table->dropColumn('company_id');
                $table->string('name', 90)->change();
                $table->string('guard_name', 90)->change();
                $table->unique(['name', 'guard_name'], 'roles_name_guard_name_unique');
            }
        });
    }
};

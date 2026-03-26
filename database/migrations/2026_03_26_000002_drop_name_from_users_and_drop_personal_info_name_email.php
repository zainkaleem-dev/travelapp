<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop columns from user_personal_infos as requested.
        if (Schema::hasTable('user_personal_infos')) {
            $drop = [];
            foreach (['first_name', 'last_name', 'email'] as $col) {
                if (Schema::hasColumn('user_personal_infos', $col)) {
                    $drop[] = $col;
                }
            }

            if ($drop !== []) {
                Schema::table('user_personal_infos', function (Blueprint $table) use ($drop) {
                    $table->dropColumn($drop);
                });
            }
        }

        // Drop `name` from users (we now use first/middle/last).
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['name']);
            });
        }

        // Enforce physical column order (MySQL).
        // Desired order:
        // id, first_name, middle_name, last_name, email, email_verified_at, password, remember_token, created_at, updated_at
        //
        // Note: MODIFY COLUMN requires full type definitions.
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `first_name` VARCHAR(255) NULL AFTER `id`");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `middle_name` VARCHAR(255) NULL AFTER `first_name`");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `last_name` VARCHAR(255) NULL AFTER `middle_name`");
        // Keep existing length (this DB uses VARCHAR(191) with a UNIQUE index).
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `email` VARCHAR(191) NOT NULL AFTER `last_name`");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `email_verified_at` TIMESTAMP NULL AFTER `email`");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(191) NOT NULL AFTER `email_verified_at`");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `remember_token` VARCHAR(100) NULL AFTER `password`");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `created_at` TIMESTAMP NULL AFTER `remember_token`");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `updated_at` TIMESTAMP NULL AFTER `created_at`");
    }

    public function down(): void
    {
        // Re-add users.name (nullable) and user_personal_infos columns.
        // Column order revert is best-effort.
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        Schema::table('user_personal_infos', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('email')->nullable()->after('last_name');
        });
    }
};


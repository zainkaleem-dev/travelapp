<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Place these right after `id` to match requested physical order.
            $table->string('first_name')->nullable()->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
        });

        // Backfill from user_personal_infos when available (preferred source).
        // We do NOT overwrite users.email here (it's already unique and canonical).
        $rows = DB::table('user_personal_infos')
            ->select(['user_id', 'first_name', 'last_name'])
            ->get();

        foreach ($rows as $r) {
            DB::table('users')
                ->where('id', $r->user_id)
                ->update([
                    'first_name' => $r->first_name,
                    'middle_name' => null,
                    'last_name' => $r->last_name,
                ]);
        }

        // Fallback: if users.first_name/last_name are still null, best-effort parse users.name.
        $users = DB::table('users')
            ->select(['id', 'name', 'first_name', 'last_name'])
            ->get();

        foreach ($users as $u) {
            $first = $u->first_name;
            $last = $u->last_name;

            if (($first !== null && $first !== '') || ($last !== null && $last !== '')) {
                continue;
            }

            $name = trim((string) ($u->name ?? ''));
            if ($name === '') {
                continue;
            }

            $parts = preg_split('/\s+/', $name) ?: [];
            $firstName = $parts[0] ?? null;
            $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : null;

            DB::table('users')
                ->where('id', $u->id)
                ->update([
                    'first_name' => $firstName,
                    'middle_name' => null,
                    'last_name' => $lastName,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
        });
    }
};


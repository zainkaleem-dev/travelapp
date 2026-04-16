<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'company_admin' => 'Company Admin',
            'branch_admin' => 'Branch Admin',
            'agent' => 'Agent',
            'user' => 'User',
        ];

        foreach ($roles as $old => $new) {
            DB::table('roles')->where('name', $old)->update(['name' => $new]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $roles = [
            'Super Admin' => 'super_admin',
            'Company Admin' => 'company_admin',
            'Branch Admin' => 'branch_admin',
            'Agent' => 'agent',
            'User' => 'user',
        ];

        foreach ($roles as $old => $new) {
            DB::table('roles')->where('name', $old)->update(['name' => $new]);
        }
    }
};

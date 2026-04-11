<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_company_branch_id')->nullable()->after('sub_company_id');
            $table->index('sub_company_branch_id');
        });
    }
 
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['sub_company_branch_id']);
            $table->dropColumn('sub_company_branch_id');
        });
    }
};


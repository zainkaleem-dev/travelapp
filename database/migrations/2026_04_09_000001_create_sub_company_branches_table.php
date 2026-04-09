<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_company_branches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('company_branch_id')->constrained('company_branches')->cascadeOnDelete();
            $table->foreignId('sub_company_id')->constrained('sub_companies')->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address', 500)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['company_id', 'company_branch_id', 'sub_company_id'], 'sub_company_branch_scope_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_company_branches');
    }
};


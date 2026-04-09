<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('company_branch_id')->constrained('company_branches')->cascadeOnDelete();

            $table->string('name');
            $table->string('code');
            $table->string('country');
            $table->string('city');
            $table->string('address', 500)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email');
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['company_id', 'company_branch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_companies');
    }
};


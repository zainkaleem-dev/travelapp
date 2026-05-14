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
        if (!Schema::hasTable('travel_policy_grade')) {
            Schema::create('travel_policy_grade', function (Blueprint $table) {
                $table->id();
                $table->foreignId('travel_policy_id')->constrained()->onDelete('cascade');
                $table->foreignId('grade_id')->constrained()->onDelete('cascade');
                $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_policy_grade');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_personal_infos', function (Blueprint $table) {
            $table->id();

            // One-to-one with users table
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique('user_id');

            // Personal Info
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();

            // Travel Documents
            $table->string('passport_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_country')->nullable();

            // Preferences
            $table->string('purpose_of_travel')->nullable();
            $table->string('seat_preference')->nullable();
            $table->string('meal_preference')->nullable();
            $table->string('preferred_cabin')->nullable();
            $table->string('preferred_airline')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_personal_infos');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_purposes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->timestamps();
        });

        DB::table('trip_purposes')->insert([
            ['key' => 'business_trip', 'label' => 'Business trip', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'personal_trip', 'label' => 'Personal trip', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'annual_trip', 'label' => 'Annual trip', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'guest', 'label' => 'Guest', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_purposes');
    }
};


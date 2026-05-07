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
        Schema::create('system_endpoints', function (Blueprint $link) {
            $link->id();
            $link->foreignId('company_id')->constrained()->onDelete('cascade');
            $link->string('endpoint_name');
            $link->string('endpoint_link');
            $link->text('description')->nullable();
            $link->boolean('is_verified')->default(false);
            $link->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_endpoints');
    }
};

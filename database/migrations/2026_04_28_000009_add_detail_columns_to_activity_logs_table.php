<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('page')->nullable()->after('activity');
            $table->string('action_name')->nullable()->after('page');
            $table->json('before_state')->nullable()->after('action_name');
            $table->json('after_state')->nullable()->after('before_state');

            $table->index('page');
            $table->index('action_name');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['page']);
            $table->dropIndex(['action_name']);
            $table->dropColumn(['page', 'action_name', 'before_state', 'after_state']);
        });
    }
};


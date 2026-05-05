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
        Schema::create('company_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->unique()->constrained('companies')->cascadeOnDelete();

            // Amadeus API
            $table->text('amadeus_url')->nullable();
            $table->text('amadeus_client_id')->nullable();
            $table->text('amadeus_client_secret')->nullable();
            $table->text('amadeus_grant_type')->nullable();

            // Mail (SMTP)
            $table->text('mail_mailer')->nullable();
            $table->text('mail_host')->nullable();
            $table->text('mail_port')->nullable();
            $table->text('mail_username')->nullable();
            $table->text('mail_password')->nullable();
            $table->text('mail_encryption')->nullable();
            $table->text('mail_from_address')->nullable();
            $table->text('mail_from_name')->nullable();

            // AWS / Storage
            $table->text('filesystem_disk')->nullable();
            $table->text('aws_access_key_id')->nullable();
            $table->text('aws_secret_access_key')->nullable();
            $table->text('aws_default_region')->nullable();
            $table->text('aws_bucket')->nullable();
            $table->text('aws_use_path_style_endpoint')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_integrations');
    }
};

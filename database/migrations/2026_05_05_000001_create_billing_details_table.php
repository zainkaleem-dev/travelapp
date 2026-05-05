<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->unique()->constrained('companies')->cascadeOnDelete();

            // Entity Information
            $table->text('entity_name')->nullable();
            $table->text('display_name')->nullable();
            $table->text('registration_number')->nullable();
            $table->text('tax_number')->nullable();

            // Currency
            $table->text('currency')->nullable();
            $table->text('currency_code')->nullable();

            // Location
            $table->text('country')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('postal_code')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();

            // Contact Person
            $table->text('first_name')->nullable();
            $table->text('middle_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->text('fax')->nullable();

            // Banking
            $table->text('bank_name')->nullable();
            $table->text('bank_account_number')->nullable();
            $table->text('bank_iban')->nullable();
            $table->text('bank_swift')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_details');
    }
};

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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_logo_path')->nullable();
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('landmark')->nullable();
            $table->string('pincode', 12)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('gstin')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('currency_symbol', 5)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('bank_details')->nullable();
            $table->string('razorpay_key')->nullable();
            $table->string('razorpay_secret')->nullable();
            $table->string('paypal_client_id')->nullable();
            $table->string('paypal_secret')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
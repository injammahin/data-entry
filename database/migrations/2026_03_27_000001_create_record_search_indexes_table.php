<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('record_search_indexes', function (Blueprint $table) {
            $table->unsignedBigInteger('record_id')->primary();
            $table->unsignedBigInteger('state_id')->nullable();

            $table->string('business_name_norm', 191)->nullable();
            $table->string('executive_first_name_norm', 120)->nullable();
            $table->string('executive_last_name_norm', 120)->nullable();
            $table->string('executive_title_norm', 191)->nullable();
            $table->string('city_norm', 120)->nullable();
            $table->string('address_norm', 191)->nullable();
            $table->string('zip_code_norm', 32)->nullable();
            $table->string('phone_norm', 32)->nullable();
            $table->string('sic_description_norm', 191)->nullable();

            $table->boolean('has_email')->default(false);
            $table->boolean('has_real_email')->default(false);
            $table->boolean('has_hashed_email')->default(false);
            $table->boolean('has_direct_mail')->default(false);

            $table->timestamps();

            $table->foreign('record_id')->references('id')->on('records')->cascadeOnDelete();
            $table->foreign('state_id')->references('id')->on('states')->nullOnDelete();

            $table->index(['state_id', 'business_name_norm'], 'rsi_state_business_idx');
            $table->index(['state_id', 'executive_first_name_norm'], 'rsi_state_exec_first_idx');
            $table->index(['state_id', 'executive_last_name_norm'], 'rsi_state_exec_last_idx');
            $table->index(['state_id', 'city_norm'], 'rsi_state_city_idx');
            $table->index(['state_id', 'zip_code_norm'], 'rsi_state_zip_idx');
            $table->index(['state_id', 'phone_norm'], 'rsi_state_phone_idx');
            $table->index(['state_id', 'address_norm'], 'rsi_state_address_idx');

            $table->index('has_email');
            $table->index('has_real_email');
            $table->index('has_hashed_email');
            $table->index('has_direct_mail');

            $table->index('executive_title_norm');
            $table->index('sic_description_norm');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('record_search_indexes');
    }
};
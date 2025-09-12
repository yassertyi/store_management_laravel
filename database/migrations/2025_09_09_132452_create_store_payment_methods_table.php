<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_payment_methods', function (Blueprint $table) {
            $table->id('spm_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('payment_option_id');

            $table->string('account_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('iban', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreign('store_id')->references('store_id')->on('stores')->onDelete('cascade');
            $table->foreign('payment_option_id')->references('option_id')->on('payment_options')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_payment_methods');
    }
};

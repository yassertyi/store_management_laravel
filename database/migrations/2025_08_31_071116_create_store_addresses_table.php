<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_addresses', function (Blueprint $table) {
            $table->id('address_id'); // معرف العنوان
            $table->unsignedBigInteger('store_id'); // معرف المتجر

            // ربط بالمتجر
            $table->foreign('store_id')
                  ->references('store_id')
                  ->on('stores')
                  ->onDelete('cascade');

            $table->string('country', 100);
            $table->string('city', 100);
            $table->string('street', 255);
            $table->string('zip_code', 20)->nullable();
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_addresses');
    }
};

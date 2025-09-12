<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_xxxxxx_create_order_addresses_table.php
public function up()
{
Schema::create('order_addresses', function (Blueprint $table) {
    $table->id('order_address_id');
    $table->unsignedBigInteger('order_id'); // يجب أن يكون نفس نوع المفتاح الأساسي
    $table->foreign('order_id')
          ->references('order_id')
          ->on('orders')
          ->onDelete('cascade');
    $table->enum('address_type', ['billing', 'shipping']);
    $table->string('first_name', 100);
    $table->string('last_name', 100);
    $table->string('email', 255);
    $table->string('phone', 20);
    $table->string('country', 100);
    $table->string('city', 100);
    $table->string('street', 255);
    $table->string('zip_code', 20)->nullable();

    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_addresses');
    }
};

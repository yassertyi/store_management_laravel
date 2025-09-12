<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
Schema::create('shipping', function (Blueprint $table) {
    $table->id('shipping_id');
    $table->unsignedBigInteger('order_id'); 
    $table->foreign('order_id')
          ->references('order_id') 
          ->on('orders')
          ->onDelete('cascade');

    $table->string('carrier', 100);
    $table->string('tracking_number', 255)->nullable();
    $table->enum('status', ['pending', 'shipped', 'in_transit', 'delivered'])->default('pending');
    $table->date('estimated_delivery')->nullable();
    $table->timestamp('actual_delivery')->nullable();
    $table->decimal('shipping_cost', 10, 2);
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};

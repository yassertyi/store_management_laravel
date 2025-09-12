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
Schema::create('payments', function (Blueprint $table) {
    $table->id('payment_id');
    $table->unsignedBigInteger('order_id'); 
    $table->foreign('order_id')
          ->references('order_id') 
          ->on('orders')
          ->onDelete('cascade');

    $table->decimal('amount', 10, 2);
    $table->string('method', 50);
    $table->string('transaction_id', 255)->nullable();
    $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
    $table->timestamp('payment_date')->nullable();
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

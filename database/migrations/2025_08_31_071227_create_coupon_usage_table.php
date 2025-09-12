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
Schema::create('coupon_usage', function (Blueprint $table) {
    $table->id('usage_id');

    $table->unsignedBigInteger('coupon_id');
    $table->foreign('coupon_id')->references('coupon_id')->on('coupons')->onDelete('cascade');

    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

    $table->unsignedBigInteger('order_id');
    $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');

    $table->timestamp('used_at');
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usage');
    }
};

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
Schema::create('reviews', function (Blueprint $table) {
    $table->id('review_id'); // المعرّف الرئيسي للتقييم

    $table->unsignedBigInteger('product_id');
    $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

    $table->unsignedBigInteger('order_id');
    $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');

    $table->integer('rating'); // التقييم (من 1 إلى 5)
    $table->string('title', 255); // عنوان التقييم
    $table->text('comment'); // تعليق التقييم
    $table->boolean('is_approved')->default(false); // موافقة الإدارة
    $table->timestamps(); // وقت الإنشاء ووقت التحديث
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

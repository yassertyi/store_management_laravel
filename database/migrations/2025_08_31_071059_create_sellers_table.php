<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id('seller_id'); // المعرّف الرئيسي للبائع
            $table->unsignedBigInteger('user_id')->unique(); // عمود المستخدم
            $table->unsignedBigInteger('store_id')->unique(); // عمود المتجر
            $table->decimal('commission_rate', 5, 2)->default(0); // نسبة العمولة

            // العلاقات
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('store_id')
                  ->references('store_id')
                  ->on('stores')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};

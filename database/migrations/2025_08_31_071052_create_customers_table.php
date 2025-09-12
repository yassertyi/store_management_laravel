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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id'); // المعرّف الرئيسي للعميل
            $table->unsignedBigInteger('user_id')->unique(); // العمود الخاص بالمستخدم
            $table->integer('loyalty_points')->default(0); // نقاط الولاء
            $table->integer('total_orders')->default(0); // إجمالي الطلبات

            // العلاقة مع جدول users
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

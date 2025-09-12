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
    Schema::create('orders', function (Blueprint $table) {
        // جدول الطلبات - يحتوي على معلومات الطلبات
        $table->id('order_id'); // المعرّف الرئيسي للطلب
        $table->unsignedBigInteger('customer_id');
        $table->foreign('customer_id')
            ->references('customer_id')
            ->on('customers')
            ->onDelete('cascade'); // ربط بجدول العملاء
        $table->string('order_number', 50)->unique(); // رقم الطلب (فريد)
        $table->decimal('total_amount', 10, 2); // المبلغ الإجمالي
        $table->decimal('subtotal', 10, 2); // المجموع الفرعي
        $table->decimal('tax_amount', 10, 2)->default(0); // مقدار الضريبة
        $table->decimal('shipping_amount', 10, 2)->default(0); // مقدار الشحن
        $table->decimal('discount_amount', 10, 2)->default(0); // مقدار الخصم
        $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending'); // حالة الطلب
        $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending'); // حالة الدفع
        $table->text('notes')->nullable(); // ملاحظات
        $table->timestamps(); // وقت الإنشاء ووقت التحديث
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

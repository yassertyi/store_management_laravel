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
    Schema::create('coupons', function (Blueprint $table) {
        // جدول الكوبونات - يحتوي على كوبونات الخصم
        $table->id('coupon_id'); // المعرّف الرئيسي للكوبون
        $table->string('code', 50)->unique(); // رمز الكوبون (فريد)
        $table->text('description')->nullable(); // وصف الكوبون
        $table->enum('discount_type', ['percentage', 'fixed']); // نوع الخصم (نسبة مئوية، مبلغ ثابت)
        $table->decimal('discount_value', 10, 2); // قيمة الخصم
        $table->decimal('min_order_amount', 10, 2)->default(0); // الحد الأدنى للطلب
        $table->decimal('max_discount_amount', 10, 2)->nullable(); // الحد الأقصى للخصم
        $table->integer('usage_limit')->nullable(); // حد الاستخدام
        $table->integer('used_count')->default(0); // عدد مرات الاستخدام
        $table->date('start_date'); // تاريخ البدء
        $table->date('expiry_date'); // تاريخ الانتهاء
        $table->boolean('is_active')->default(true); // الحالة (نشط/غير نشط)
        $table->timestamps(); // وقت الإنشاء ووقت التحديث
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

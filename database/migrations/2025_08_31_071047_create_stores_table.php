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
 Schema::create('stores', function (Blueprint $table) {
    $table->id('store_id'); // المعرّف الرئيسي للمتجر
    $table->unsignedBigInteger('user_id'); // العمود صاحب المتجر

    $table->string('store_name', 255); // اسم المتجر
    $table->text('description')->nullable(); // وصف المتجر
    $table->string('logo', 500)->nullable(); // شعار المتجر
    $table->string('banner', 500)->nullable(); // بانر المتجر
    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); // حالة المتجر
    $table->timestamps(); // وقت الإنشاء ووقت التحديث

    // بعد إنشاء العمود فقط أضف الـ foreign key
    $table->foreign('user_id')
          ->references('user_id')
          ->on('users')
          ->onDelete('cascade');
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};

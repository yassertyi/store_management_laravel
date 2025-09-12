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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // المعرّف الرئيسي للمستخدم
            $table->string('name', 255); // اسم المستخدم
            $table->string('email', 255)->unique(); // البريد الإلكتروني
            $table->string('password', 255); // كلمة المرور
            $table->string('phone', 20)->nullable(); // رقم الهاتف
            $table->tinyInteger('user_type')->default(0); // نوع المستخدم: 0=عميل،1=بائع،2=مسؤول
            $table->boolean('active')->default(true); // حالة التنشيط
            $table->string('profile_photo', 500)->nullable(); // صورة الملف الشخصي
            $table->tinyInteger('gender')->nullable(); // الجنس: 0=ذكر،1=أنثى
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

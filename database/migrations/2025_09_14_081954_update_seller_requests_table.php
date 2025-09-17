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
        Schema::table('seller_requests', function (Blueprint $table) {
            // بيانات المستخدم
            $table->string('password', 255)->nullable()->after('email'); // كلمة المرور
            $table->tinyInteger('gender')->nullable()->after('phone'); // الجنس: 0=ذكر،1=أنثى
            $table->string('profile_photo', 500)->nullable()->after('gender'); // صورة الملف الشخصي

            // بيانات المتجر
            $table->string('logo', 500)->nullable()->after('store_description'); // شعار المتجر
            $table->string('banner', 500)->nullable()->after('logo'); // بانر المتجر
            $table->string('business_license_number', 100)->nullable()->after('banner'); // رقم الرخصة التجارية
            $table->string('document_path', 500)->nullable()->after('business_license_number'); // مسار المستندات الرسمية

            // بيانات العنوان
            $table->string('country', 100)->nullable()->after('document_path');
            $table->string('state', 100)->nullable()->after('country');
            $table->string('city', 100)->nullable()->after('state');
            $table->string('street', 255)->nullable()->after('city');
            $table->string('zip_code', 20)->nullable()->after('street');

            // بيانات الاتصال
            $table->string('country_code', 10)->nullable()->after('zip_code');
            $table->string('phone_number', 20)->nullable()->after('country_code');

            // صور إضافية للمتجر
            $table->json('additional_images')->nullable()->after('phone_number'); // تخزين أكثر من صورة كـ JSON
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_requests', function (Blueprint $table) {
            // إزالة الأعمدة إذا تم التراجع عن التعديل
            $table->dropColumn([
                'password',
                'gender',
                'profile_photo',
                'logo',
                'banner',
                'business_license_number',
                'document_path',
                'country',
                'state',
                'city',
                'street',
                'zip_code',
                'country_code',
                'phone_number',
                'additional_images',
            ]);
        });
    }
};

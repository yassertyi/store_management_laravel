<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            // تحويل الحقل status إلى tinyInteger
            $table->tinyInteger('status')->default(0)->change();

            // تحويل الحقل priority إلى tinyInteger
            $table->tinyInteger('priority')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            // إعادة الحقول كما كانت نصوص في حال الرجوع
            $table->string('status')->default('مفتوحة')->change();
            $table->string('priority')->default('متوسطة')->change();
        });
    }
};

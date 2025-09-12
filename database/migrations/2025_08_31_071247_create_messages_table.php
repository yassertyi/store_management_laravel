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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id'); // المفتاح الأساسي

            // الربط بالدردشة
            $table->unsignedBigInteger('chat_id');
            $table->foreign('chat_id')->references('chat_id')->on('chats')->onDelete('cascade');

            // المرسل
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade');

            // المستلم
            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('user_id')->on('users')->onDelete('cascade');

            // محتوى الرسالة
            $table->text('content');

            // حالة القراءة
            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // حذف المفاتيح الخارجية أولاً
            $table->dropForeign(['chat_id']);
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
        });

        // حذف الجدول
        Schema::dropIfExists('messages');
    }
};

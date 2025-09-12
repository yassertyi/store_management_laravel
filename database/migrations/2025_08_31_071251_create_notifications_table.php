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
Schema::create('notifications', function (Blueprint $table) {
    $table->id('notification_id');

    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

    $table->string('title', 255);
    $table->text('content');
    $table->string('type', 50);
    $table->boolean('is_read')->default(false);
    $table->integer('related_id')->nullable();
    $table->string('related_type', 50)->nullable();
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

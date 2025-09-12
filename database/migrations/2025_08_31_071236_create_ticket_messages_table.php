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
Schema::create('ticket_messages', function (Blueprint $table) {
    $table->id('message_id');

    $table->unsignedBigInteger('ticket_id');
    $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets')->onDelete('cascade');

    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

    $table->text('message');
    $table->boolean('is_read')->default(false);
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};

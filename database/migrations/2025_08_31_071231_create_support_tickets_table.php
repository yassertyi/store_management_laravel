<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_xxxxxx_create_support_tickets_table.php
public function up()
{
Schema::create('support_tickets', function (Blueprint $table) {
    $table->id('ticket_id');

    $table->unsignedBigInteger('customer_id');
    $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');

    $table->string('subject', 255);
    $table->text('description');
    $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

    $table->timestamps();
});

}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};

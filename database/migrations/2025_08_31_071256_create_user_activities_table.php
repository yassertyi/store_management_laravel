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
Schema::create('user_activities', function (Blueprint $table) {
    $table->id('activity_id');

    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

    $table->string('activity_type', 100);
    $table->text('description');
    $table->string('ip_address', 45);
    $table->text('user_agent');
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};

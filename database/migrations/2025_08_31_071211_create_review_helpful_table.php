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
Schema::create('review_helpful', function (Blueprint $table) {
    $table->id('helpful_id');

    $table->unsignedBigInteger('review_id');
    $table->foreign('review_id')->references('review_id')->on('reviews')->onDelete('cascade');

    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

    $table->boolean('is_helpful');
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_helpful');
    }
};

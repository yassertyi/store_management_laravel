<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_xxxxxx_create_wishlists_table.php
public function up()
{
Schema::create('wishlists', function (Blueprint $table) {
    $table->id('wishlist_id');

    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

    $table->unsignedBigInteger('product_id');
    $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};

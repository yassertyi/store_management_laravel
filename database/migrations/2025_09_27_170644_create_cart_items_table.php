<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('store_id')->constrained('stores', 'store_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants', 'variant_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->index(['user_id', 'store_id']);
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id'); // معرف المنتج
            $table->unsignedBigInteger('store_id'); // معرف المتجر
            $table->unsignedBigInteger('category_id'); // معرف الفئة

            // ربط بالمتجر
            $table->foreign('store_id')
                  ->references('store_id')
                  ->on('stores')
                  ->onDelete('cascade');

            // ربط بالفئة
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->onDelete('cascade');

            $table->string('title', 255); 
            $table->text('description'); 
            $table->decimal('price', 10, 2); 
            $table->decimal('compare_price', 10, 2)->nullable(); 
            $table->decimal('cost', 10, 2)->nullable(); 
            $table->string('sku', 100)->unique(); 
            $table->string('barcode', 100)->nullable(); 
            $table->integer('stock')->default(0); 
            $table->decimal('weight', 10, 2)->nullable(); 
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active'); 
            $table->boolean('is_featured')->default(false); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

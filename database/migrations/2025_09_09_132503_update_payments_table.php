<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // إضافة أعمدة جديدة
            $table->unsignedBigInteger('store_payment_method_id')->nullable()->after('order_id');
            $table->decimal('discount', 10, 2)->default(0)->after('amount');
            $table->decimal('total_amount', 10, 2)->nullable()->after('discount');
            $table->string('currency', 25)->default('USD')->after('total_amount');
            $table->enum('type', ['online', 'cash'])->default('cash')->after('status');
            $table->text('note')->nullable()->after('transaction_id');

            // تعديل enum status
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->change();

            // إضافة مفتاح خارجي
            $table->foreign('store_payment_method_id')
                  ->references('spm_id')
                  ->on('store_payment_methods')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['store_payment_method_id']);
            $table->dropColumn([
                'store_payment_method_id',
                'discount',
                'total_amount',
                'currency',
                'type',
                'note',
            ]);
        });
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'store_payment_method_id',
        'amount',
        'discount',
        'total_amount',
        'currency',
        'method',
        'transaction_id',
        'status',
        'type',
        'note',
        'payment_date',
    ];

    // العلاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // العلاقة مع طرق الدفع
    public function storePaymentMethod()
    {
        return $this->belongsTo(StorePaymentMethod::class, 'store_payment_method_id', 'spm_id');
    }
}

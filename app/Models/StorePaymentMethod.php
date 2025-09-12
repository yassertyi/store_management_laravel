<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePaymentMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'spm_id';

    protected $fillable = [
        'store_id',
        'payment_option_id',
        'account_name',
        'account_number',
        'iban',
        'description',
        'is_active',
    ];

    // العلاقة مع المتجر
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    // العلاقة مع خيارات الدفع
    public function paymentOption()
    {
        return $this->belongsTo(PaymentOption::class, 'payment_option_id', 'option_id');
    }

    // العلاقة مع الدفعات
    public function payments()
    {
        return $this->hasMany(Payment::class, 'store_payment_method_id', 'spm_id');
    }
}

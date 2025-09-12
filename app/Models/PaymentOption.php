<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
    use HasFactory;

    protected $primaryKey = 'option_id';

    protected $fillable = [
        'method_name',
        'logo',
        'currency',
        'is_active',
    ];

    public function storePaymentMethods()
    {
        return $this->hasMany(StorePaymentMethod::class, 'payment_option_id', 'option_id');
    }
}

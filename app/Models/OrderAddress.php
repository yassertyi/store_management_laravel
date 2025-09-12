<?php
// app/Models/OrderAddress.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_address_id';
    protected $fillable = [
        'order_id', 'address_type', 'first_name', 'last_name',
        'email', 'phone', 'country', 'city', 'street', 'zip_code'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
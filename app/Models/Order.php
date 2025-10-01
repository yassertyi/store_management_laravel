<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    protected $fillable = [
        'customer_id', 'order_number', 'total_amount', 'subtotal',
        'tax_amount', 'shipping_amount', 'discount_amount',
        'status', 'payment_status', 'notes'
    ];
    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function addresses()
    {
        return $this->hasMany(OrderAddress::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'order_id');
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class, 'order_id');
    }
     public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
    
    public function payment()
{
    return $this->hasOne(Payment::class, 'order_id');
}


public function orderAddresses()
{
    return $this->hasMany(OrderAddress::class, 'order_id');
}
}
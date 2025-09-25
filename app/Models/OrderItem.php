<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    protected $fillable = [
        'order_id', 'product_id', 'variant_id', 'quantity',
        'unit_price', 'total_price'
    ];
    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // علاقة مع التقييمات - إصلاح هنا
    public function review()
    {
        return $this->hasOne(Review::class, 'order_id', 'order_id')
                    ->where('product_id', $this->product_id);
    }
}
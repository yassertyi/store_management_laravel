<?php
// app/Models/CouponUsage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CouponUsage extends Model
{
    use HasFactory;

    protected $table = 'coupon_usage';
    protected $primaryKey = 'usage_id';
    protected $fillable = ['coupon_id', 'user_id', 'order_id', 'used_at'];
    protected $casts = ['used_at' => 'datetime'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

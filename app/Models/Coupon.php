<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $primaryKey = 'coupon_id';
    
    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'start_date',
        'expiry_date',
        'is_active',
        'used_count'
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
        'used_count' => 'integer',
        'usage_limit' => 'integer'
    ];

    // العلاقة مع استخدامات الكوبون
    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id', 'coupon_id');
    }

    // التحقق إذا كان الكوبون صالح للاستخدام
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->start_date && $this->start_date->gt(now())) {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->lt(now())) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    // حساب قيمة الخصم
    public function calculateDiscount($subtotal)
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($subtotal * $this->discount_value) / 100;
            return $this->max_discount_amount ? min($discount, $this->max_discount_amount) : $discount;
        } else {
            return $this->discount_value;
        }
    }
}
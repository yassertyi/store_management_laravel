<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_item_id';
    
    protected $fillable = [
        'user_id',
        'store_id',
        'product_id',
        'variant_id',
        'quantity',
        'price'
    ];

    // إضافة خاصية total محسوبة
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // إضافة علاقة مع الصور
    public function getImageAttribute()
    {
        $product = $this->product;
        if ($product && $product->images->count() > 0) {
            return $product->images->first()->image_path;
        }
        return 'static/images/placeholder.jpg';
    }

    // الحصول على عنوان المنتج
    public function getProductTitleAttribute()
    {
        return $this->product ? $this->product->title : 'منتج غير متوفر';
    }

    // الحصول على اسم المتجر
    public function getStoreNameAttribute()
    {
        return $this->store ? $this->store->store_name : 'متجر غير متوفر';
    }
}
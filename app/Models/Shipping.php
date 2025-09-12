<?php
// app/Models/Shipping.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    // إذا كان اسم الجدول في قاعدة البيانات مفرد وليس جمع
    protected $table = 'shipping'; 

    // المفتاح الأساسي للجدول
    protected $primaryKey = 'shipping_id';

    // الحقول القابلة للملء
    protected $fillable = [
        'order_id', 
        'carrier', 
        'tracking_number', 
        'status',
        'estimated_delivery', 
        'actual_delivery', 
        'shipping_cost'
    ];

    // تحويل أنواع الحقول تلقائيًا
    protected $casts = [
        'estimated_delivery' => 'date',       // yyyy-mm-dd
        'actual_delivery' => 'datetime',      // yyyy-mm-dd hh:mm:ss
        'shipping_cost' => 'decimal:2'
    ];

    // العلاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // إذا أردت يمكن إضافة scope جاهز للتصفية حسب الحالة
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

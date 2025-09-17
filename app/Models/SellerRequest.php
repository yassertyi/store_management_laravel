<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';

    protected $fillable = [
        // بيانات المستخدم
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'profile_photo',

        // بيانات المتجر
        'store_name',
        'store_description',
        'logo',
        'banner',
        'business_license_number',
        'document_path',

        // بيانات العنوان
        'country',
        'state',
        'city',
        'street',
        'zip_code',

        // بيانات الاتصال
        'country_code',
        'phone_number',

        // صور إضافية
        'additional_images',

        // حالة الطلب
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'additional_images' => 'array', 
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $primaryKey = 'testimonial_id';
    
    protected $fillable = [
        'customer_name', 
        'customer_image', 
        'location', 
        'content', 
        'rating', 
        'is_active', 
        'sort_order'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer'
    ];

    // تأكد من أن السكوب يعمل بشكل صحيح
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }
}
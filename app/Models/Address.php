<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'address_id';
    protected $fillable = [
        'user_id', 'title', 'first_name', 'last_name', 'phone',
        'country', 'city', 'street', 'apartment', 'zip_code', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $primaryKey = 'store_id';
    protected $fillable = ['user_id', 'store_name', 'description', 'logo', 'banner', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function phones()
    {
        return $this->hasMany(StorePhone::class, 'store_id');
    }

    public function addresses()
    {
        return $this->hasMany(StoreAddress::class, 'store_id');
    }
    
    public function seller()
{
    return $this->hasOne(Seller::class, 'store_id', 'store_id');
}
}

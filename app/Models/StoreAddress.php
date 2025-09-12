<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAddress extends Model
{
    use HasFactory;

    protected $primaryKey = 'address_id';
    protected $fillable = ['store_id', 'country', 'city', 'street', 'zip_code', 'is_primary'];
    protected $casts = ['is_primary' => 'boolean'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}

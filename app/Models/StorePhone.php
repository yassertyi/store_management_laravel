<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePhone extends Model
{
    use HasFactory;

    protected $primaryKey = 'phone_id';
    protected $fillable = ['store_id', 'phone', 'is_primary'];
    protected $casts = ['is_primary' => 'boolean'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}

<?php
// app/Models/SellerRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';
    protected $fillable = [
        'name', 'email', 'phone', 'store_name', 'store_description',
        'status', 'approved_by', 'rejection_reason'
    ];

}
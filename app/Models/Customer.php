<?php
// app/Models/Customer.php

namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';
    protected $fillable = ['user_id', 'loyalty_points', 'total_orders'];

    // علاقة مع User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقات إضافية حسب الحاجة
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'customer_id');
    }
}

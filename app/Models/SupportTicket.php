<?php
// app/Models/SupportTicket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';
    protected $fillable = [
        'customer_id', 'subject', 'description', 'status',
        'priority', 'assigned_to'
    ];

    // علاقة التذكرة بالعميل
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // علاقة التذكرة بالبائع المسؤول
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');
    }

    // علاقة التذكرة بالرسائل المرتبطة بها
    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }
}

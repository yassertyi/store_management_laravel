<?php
// app/Models/Message.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'message_id';
    protected $fillable = ['chat_id', 'sender_id', 'receiver_id', 'content', 'is_read'];
    protected $casts = ['is_read' => 'boolean'];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // إضافة علاقة المستلم
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}

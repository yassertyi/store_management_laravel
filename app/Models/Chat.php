<?php
// app/Models/Chat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $primaryKey = 'chat_id';
    protected $fillable = ['user_id',  'subject', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }
}
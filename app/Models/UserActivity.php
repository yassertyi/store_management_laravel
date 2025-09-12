<?php
// app/Models/UserActivity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $primaryKey = 'activity_id';
    protected $fillable = [
        'user_id', 'activity_type', 'description', 'ip_address', 'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
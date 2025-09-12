<?php
// app/Models/ReviewHelpful.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewHelpful extends Model
{
    use HasFactory;

    protected $table = 'review_helpful'; 
    protected $primaryKey = 'helpful_id';
    protected $fillable = ['review_id', 'user_id', 'is_helpful'];
    protected $casts = ['is_helpful' => 'boolean'];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

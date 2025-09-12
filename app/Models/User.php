<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'user_type', 
        'active', 'profile_photo', 'gender'
    ];
    protected $hidden = ['password'];
    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // العلاقات
    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function seller()
    {
        return $this->hasOne(Seller::class, 'user_id');
    }



    public function stores()
    {
        return $this->hasMany(Store::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function reviewHelpfuls()
    {
        return $this->hasMany(ReviewHelpful::class, 'user_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class, 'user_id');
    }

    public function ticketMessages()
    {
        return $this->hasMany(TicketMessage::class, 'user_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class, 'user_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'customer_id');
    }
}
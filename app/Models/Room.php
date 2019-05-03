<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'description', 'price', 'number'
    ];

    public $timestamps = false;

    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_rooms', 'room_id', 'photo_id');
    }

    public function customers()
    {
        return $this->belongsToMany(User::class, 'orders', 'room_id', 'user_id');
    }

    public function free()
    {
        return $this->where('free', 1);
//        return $this->whereDoesntHave('customers');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'room_id', 'id');
    }

    public function latest_order()
    {
        return $this->hasMany(Order::class, 'room_id', 'id')->latest('created_at');
    }
}

<?php

namespace App\Models;

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
        return $this->belongsToMany(User::class, 'users', 'user_id', 'id');
    }

    public function free()
    {
        return $this->whereDoesntHave('customers');
    }
}

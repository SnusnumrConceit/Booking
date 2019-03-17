<?php

namespace App\Models;

use App\Models\Room;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['url'];

    public $timestamps = false;

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'rooms', 'photo_id', 'room_id');
    }
}

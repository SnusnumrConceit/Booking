<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoRoom extends Model
{
    public $fillable = ['room_id', 'photo_id'];

    public $timestamps = false;
}

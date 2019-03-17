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
        return $this->belongsToMany(Photo::class, 'photos', 'photo_id', 'id');
    }
}

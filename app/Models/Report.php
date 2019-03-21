<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['description', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

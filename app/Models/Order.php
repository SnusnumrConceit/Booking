<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'room_id',
        'status', 'note_date',
        'days'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function sortByRoom($column, $type)
    {
        return $this->join('rooms', 'orders.room_id', '=', 'rooms.id')
            ->orderBy("rooms.$column", $type)
            ->select('orders.*');
    }

    public function sortByCustomer($type)
    {
        return $this->join('users', 'orders.user_id', '=', 'users.id')
            ->orderBy('users.last_name', $type)
            ->select('orders.*');
    }

    public function searchByCustomer($keyword)
    {
        return $this->join('users', 'orders.user_id', '=', 'users.id')
            ->where('users.last_name', 'LIKE', $keyword.'%')
            ->select('orders.*');
    }
}

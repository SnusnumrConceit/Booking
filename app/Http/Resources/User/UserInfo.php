<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Order\OrderCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'orders' => new OrderCollection($this->orders),
            'full_name' => $this->getFullName($this)
        ];
    }

    public function getFullName($user)
    {
        return $user->last_name.' '.$user->first_name.' '.$user->middle_name;
    }
}

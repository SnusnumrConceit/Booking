<?php

namespace App\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'room' => $this->room,
            'status' => $this->getStatus($this->status),
            'status_type' => $this->status,
            'customer' => $this->customer,
            'note_date' => Carbon::parse($this->note_date)->format('d.m.Y H:i'),
            'days' => $this->days.' дней'
        ];
    }

    public function getStatus($status)
    {
        $result = '';
        switch ($status) {
            case 0: $result = 'Отказано'; break;
            case 1: $result = 'Забронировано'; break;
            case 2: $result = 'Оплачено'; break;
        }
        return $result;
    }
}

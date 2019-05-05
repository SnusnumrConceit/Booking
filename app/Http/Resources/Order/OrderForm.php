<?php

namespace App\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderForm extends JsonResource
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
            'id'        =>   $this->id,
            'days'      =>   $this->days,
            'room_id'   =>   $this->room_id,
            'customer'  =>   $this->customer,
            'room'      =>   $this->room,
            'status'    =>   $this->status,
            'user_id'   =>   $this->user_id,
            'note_date' =>   Carbon::parse($this->note_date)->format('Y-m-d'),
            'note_time' =>   $this->formatToNoteTime($this->note_date)
        ];
    }

    public function formatToNoteTime($date)
    {
        $time = Carbon::parse($date)->format('H:i:s');
        $time = (object) [
            'HH' => substr($time, 0, 2),
            'mm' => substr($time, 3, 2)
        ];
        return $time;
    }
}

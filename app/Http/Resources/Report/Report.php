<?php

namespace App\Http\Resources\Report;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Report extends JsonResource
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
            'id' => $this->id,
            'description' => substr($this->description, 0, 30).'...',
            'user' => $this->user || null,
            'created_at' => $this->convertDate($this->created_at)
        ];
    }

    public function convertDate($date)
    {
       if (Carbon::now()->diffInDays($date) == 0) {
           return Carbon::parse($date)->diffForHumans();
       } else {
           return Carbon::parse($date)->format('d.m.Y');
       }
    }
}

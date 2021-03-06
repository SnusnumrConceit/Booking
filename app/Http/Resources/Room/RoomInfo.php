<?php

namespace App\Http\Resources\Room;

use App\Http\Resources\Photo\PhotoCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomInfo extends JsonResource
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
            'number' => $this->number,
            'description' => $this->description,
            'price' => $this->price,
            'photos' => new PhotoCollection($this->photos)
        ];
    }
}

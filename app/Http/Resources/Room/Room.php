<?php

namespace App\Http\Resources\Room;

use App\Http\Resources\Photo\PhotoCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Room extends JsonResource
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
            'number' => $this->number,
            'photos' => new PhotoCollection($this->photos)
        ];
    }
}

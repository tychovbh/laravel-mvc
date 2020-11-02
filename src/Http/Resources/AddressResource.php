<?php

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'zipcode' => $this->zipcode,
            'house_number' => $this->house_number,
            'street' => $this->street,
            'city' =>$this->city,
            'country' => $this->country
        ];
    }
}

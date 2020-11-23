<?php

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $country = $this->country;
        return [
            'id' => $this->id,
            'zipcode' => $this->zipcode,
            'house_number' => $this->house_number,
            'street' => $this->street,
            'country' => $this->country,
            'city' => $this->city,
        ];
    }
}

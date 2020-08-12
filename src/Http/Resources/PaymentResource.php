<?php

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'url' => $this->when($this->external_id, function () {
                return $this->url;
            }),
            'status' => $this->status,
            'description' => $this->description,
            'amount' => $this->amount,
            'external_id' => $this->external_id,
        ];
    }
}

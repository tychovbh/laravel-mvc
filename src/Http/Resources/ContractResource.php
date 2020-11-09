<?php

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file' => $this->file,
            'status' => $this->status,
            'signed_at' => $this->signed_at,
            'options' => $this->options
        ];
    }
}

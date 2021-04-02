<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DatabaseResource extends JsonResource
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
            'name' => $this->name,
            'label' => $this->label,
        ];
    }
}

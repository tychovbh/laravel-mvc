<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
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
            'label' => $this->label,
            'name' => $this->name,
            'description' => $this->description,
            'route' => $this->route,
            'fields' => FieldResource::collection($this->fields),
        ];
    }
}

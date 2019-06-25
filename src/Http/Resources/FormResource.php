<?php

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
        $route = explode('_', $this->name);

        return [
            'label' => $this->label,
            'name' => $this->name,
            'description' => $this->description,
            'route' => (sizeof($route) > 1 ? $route[1] : $route[0]) . '.store'
        ];
    }
}

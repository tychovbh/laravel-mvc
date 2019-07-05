<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
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
            'placeholder' => $this->placeholder,
            'required' => $this->required,
            'input' => $this->input ? new InputResource($this->input) : [],
        ];
    }
}
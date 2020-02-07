<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FieldResource extends Resource
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
            'properties' => $this->properties,
            'element' => new ElementResource($this->element),
        ];
    }
}

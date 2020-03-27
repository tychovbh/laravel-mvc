<?php

namespace Tychovbh\Mvc\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RoleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

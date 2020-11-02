<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tychovbh\Mvc\Http\Controllers\AbstractController;

class AddressController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        $zipcode = $request->get('zipcode');
        $house_number = $request->get('house_number');

        try {
            return new $this->resource($this->repository::withParams($request->toArray())->first());
        } catch (ModelNotFoundException $exception) {
            return parent::store($request);
        }
    }
}

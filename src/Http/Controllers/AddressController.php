<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        try {
            $test = $this->resource($this->repository::withParams($request->toArray()));
            return new $this->resource($this->repository::withParams($request->toArray())->first());
        } catch (ModelNotFoundException $exception) {
            return parent::store($request);
        }
    }
}

<?php
namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface ControllerInterface
{
    /**
     * List all resources.
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection;

    /**
     * Show User Resource.
     * @param Request $request
     * @param string $id
     * @return JsonResource
     */
    public function show(Request $request, string $id): JsonResource;

    /**
     * Store new Resource.
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource;

    /**
     * Update existing Resource.
     * @param Request $request
     * @param string $id
     * @return JsonResource
     */
    public function update(Request $request, string $id): JsonResource;

    /**
     * Destroy Resource.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse;
}

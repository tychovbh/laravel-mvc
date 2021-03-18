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
     * @param int|string $id
     * @return JsonResource
     */
    public function show(Request $request, $id): JsonResource;

    /**
     * Create form.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse;

    /**
     * Edit form.
     * @param Request $request
     * @param string|int $id
     * @return JsonResponse
     */
    public function edit(Request $request, $id): JsonResponse;

    /**
     * Store new Resource.
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource;

    /**
     * Update existing Resource.
     * @param Request $request
     * @param string|int $id
     * @return JsonResource
     */
    public function update(Request $request, $id): JsonResource;

    /**
     * Destroy Resource.
     * @param string|int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse;
}

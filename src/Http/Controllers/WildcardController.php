<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WildcardController extends AbstractController
{
    /**
     * Show record.
     * @param Request $request
     * @param int|string $id
     * @return JsonResource
     */
    public function show(Request $request, $id): JsonResource
    {
        return parent::show($request, $request->route('id'));
    }

    /**
     * Create form.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->offsetSet('database', $request->route('connection'));
        $request->offsetSet('table', $request->route('table'));
        return parent::create($request);
    }

    /**
     * Edit form.
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function edit(Request $request, $id): JsonResponse
    {
        $request->offsetSet('database', $request->route('connection'));
        $request->offsetSet('table', $request->route('table'));
        return parent::edit($request, $request->route('id'));
    }

    /**
     * Update record.
     * @param Request $request
     * @param int|string $id
     * @return JsonResource
     */
    public function update(Request $request, $id): JsonResource
    {
        return parent::update($request, $request->route('id'));
    }

    /**
     * Destroy record.
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return parent::destroy(request()->route('id'));
    }
}

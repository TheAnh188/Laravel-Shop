<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;


/**
 * Interface PermissionServiceInterface
 * @package App\Services\Interfaces
 */
interface PermissionServiceInterface
{
    public function paginate($request);
    public function create(StorePermissionRequest $request);
    public function update(int $id, UpdatePermissionRequest $request);
    public function destroy(int $id);
}

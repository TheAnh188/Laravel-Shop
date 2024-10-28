<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserCatalogueRequest;


/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueServiceInterface
{
    public function paginate($request);
    public function create(StoreUserCatalogueRequest $request);
    public function update(int $id, UpdateUserCatalogueRequest $request);
    public function destroy(int $id);
    public function setStatus($post);
    public function setStatusAll($post);
    public function setPermission($request);
}

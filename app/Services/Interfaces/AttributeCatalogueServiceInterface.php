<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;


/**
 * Interface AttributeCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueServiceInterface
{
    public function paginate($request, $language_id);
    public function create(StoreAttributeCatalogueRequest $request, $language_id);
    public function update(int $id, UpdateAttributeCatalogueRequest $request, $language_id);
    public function destroy(int $id, $language_id);
    public function setStatus($attribute);
    public function setStatusAll($attribute);


}

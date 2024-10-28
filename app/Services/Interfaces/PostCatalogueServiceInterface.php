<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;


/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueServiceInterface
{
    public function paginate($request, $language_id);
    public function create(StorePostCatalogueRequest $request, $language_id);
    public function update(int $id, UpdatePostCatalogueRequest $request, $language_id);
    public function destroy(int $id, $language_id);
    public function setStatus($post);
    public function setStatusAll($post);


}

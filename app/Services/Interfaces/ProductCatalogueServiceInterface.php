<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreProductCatalogueRequest;
use App\Http\Requests\UpdateProductCatalogueRequest;


/**
 * Interface ProductCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueServiceInterface
{
    public function paginate($request, $language_id);
    public function create(StoreProductCatalogueRequest $request, $language_id);
    public function update(int $id, UpdateProductCatalogueRequest $request, $language_id);
    public function destroy(int $id, $language_id);
    public function setStatus($post);
    public function setStatusAll($post);


}

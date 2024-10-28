<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;


/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductServiceInterface
{
    public function paginate($request, $language_id);
    public function create(StoreProductRequest $request, $language_id);
    public function update(int $id, UpdateProductRequest $request, $language_id);
    public function destroy(int $id);
    public function setStatus($product);
    public function setStatusAll($product);


}

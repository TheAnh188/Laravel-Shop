<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;


/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeServiceInterface
{
    public function paginate($request, $language_id);
    public function create(StoreAttributeRequest $request, $language_id);
    public function update(int $id, UpdateAttributeRequest $request, $language_id);
    public function destroy(int $id);
    public function setStatus($attribute);
    public function setStatusAll($attribute);


}

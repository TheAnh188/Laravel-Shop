<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;


/**
 * Interface PostServiceInterface
 * @package App\Services\Interfaces
 */
interface PostServiceInterface
{
    public function paginate($request, $language_id);
    public function create(StorePostRequest $request, $language_id);
    public function update(int $id, UpdatePostRequest $request, $language_id);
    public function destroy(int $id);
    public function setStatus($post);
    public function setStatusAll($post);


}

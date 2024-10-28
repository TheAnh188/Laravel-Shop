<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;


/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserServiceInterface
{
    public function paginate($request);
    public function create(StoreUserRequest $request);
    public function update(int $id, UpdateUserRequest $request);
    public function destroy(int $id);
    public function setStatus($post);
    public function setStatusAll($post);


}

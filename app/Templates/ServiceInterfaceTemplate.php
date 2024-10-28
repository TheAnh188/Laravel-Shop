<?php

namespace App\Services\Interfaces;
use App\Http\Requests\Store{Module}Request;
use App\Http\Requests\Update{Module}Request;


/**
 * Interface {Module}ServiceInterface
 * @package App\Services\Interfaces
 */
interface {Module}ServiceInterface
{
    public function paginate($request, $language_id);
    public function create(Store{Module}Request $request);
    public function update(int $id, Update{Module}Request $request);
    public function destroy(int $id);
    public function setStatus($post);
    public function setStatusAll($post);


}

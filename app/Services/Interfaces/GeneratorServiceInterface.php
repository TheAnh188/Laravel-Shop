<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreGeneratorRequest;
use App\Http\Requests\UpdateGeneratorRequest;


/**
 * Interface GeneratorServiceInterface
 * @package App\Services\Interfaces
 */
interface GeneratorServiceInterface
{
    public function paginate($request);
    public function create(StoreGeneratorRequest $request);
    public function update(int $id, UpdateGeneratorRequest $request);
    public function destroy(int $id);
}

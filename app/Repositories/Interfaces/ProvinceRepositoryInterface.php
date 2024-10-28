<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProvinceServiceInterface
 * @package App\Services\Interfaces
 */
interface ProvinceRepositoryInterface
{
    public function all(array $relation = [], $deleted_at = true, $language_id = 0);

    public function findById(string $id, array $columns = ['*'], array $relations = []);
}

<?php

namespace App\Repositories\Interfaces;

/**
 * Interface DistrictServiceInterface
 * @package App\Services\Interfaces
 */
interface DistrictRepositoryInterface
{
    public function all(array $relation = [], $deleted_at = true, $language_id = 0);
    public function findById(string $id, array $columns = ['*'], array $relations = []);
}

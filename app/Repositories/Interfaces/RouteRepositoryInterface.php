<?php

namespace App\Repositories\Interfaces;

/**
 * Interface RouteServiceInterface
 * @package App\Services\Interfaces
 */
interface RouteRepositoryInterface
{
    public function all(array $relation = [], $deleted_at = true, $language_id = 0);
    public function findById(string $id, array $columns = ['*'], array $relations = []);
    public function findByCondition(array $condition = []);
    public function create(array $payload = []);
    public function update(int $id, array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function forceDeleteByCondition(array $condition = []);
    public function deleteByCondition(array $condition = []);
    public function updateByWhereIN(string $whereInField = '', array $whereIn = [], array $payload = []);
    public function updateByWhere($condition = [], array $payload = []);

}

<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
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
    public function paginate(array $columns = ['*'], array $condition = [], int $perpage = 20, array $orderBy = [], array $extend = [], array $join = [], array $relations = [], array $rawQuery = []);
    public function updateByWhereIN(string $whereInField = '', array $whereIn = [], array $payload = []);
    public function createPivot($model, array $payload = [], string $relation = '');
    public function updateByWhere($condition = [], array $payload = []);
    public function createBatch(array $payload = []);
    public function updateOrInsert(array $payload = [], array $condition = []);

}

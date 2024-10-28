<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeRepositoryInterface
 * @package App\Services\Interfaces
 */
interface AttributeRepositoryInterface
{
    public function findById(string $id, array $columns = ['*'], array $relations = []);
    public function create(array $payload = []);
    public function update(int $id, array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function paginate(array $columns = ['*'], array $condition = [], int $perpage = 20, array $orderBy = [], array $extend = [], array $join = [], array $relations = [], array $rawQuery = []);
    public function updateByWhereIN(string $whereInField = '', array $whereIn = [], array $payload = []);
    public function createPivot($model, array $payload = [], string $relation = '');
    public function getAttributeById(int $id = 0, $language_id = 0);
    public function searchAttributes(string $keyword = '', array $option = [], int $language_id);
    public function findAttributeByIdArray(array $attributeIdArray = [], int $language_id = 0);
}

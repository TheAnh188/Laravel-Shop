<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserRepositoryInterface
{
    public function findById(string $id, array $columns = ['*'], array $relations = []);
    public function create(array $payload = []);
    public function update(int $id, array $payload = []);
    public function delete(int $id = 0);
    public function forceDelete(int $id = 0);
    public function paginate(array $columns = ['*'], array $condition = [], int $perpage = 20, array $orderBy = [], array $extend = [], array $join = [], array $relations = [], array $rawQuery = []);
    public function updateByWhereIN(string $whereInField = '', array $whereIn = [], array $payload = []);

}

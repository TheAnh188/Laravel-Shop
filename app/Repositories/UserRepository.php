<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

/**
 * Class UserRepository
 * @package App\Services
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    protected $model;

    public function __construct(User $model) {
        $this->model = $model;
    }

    public function paginate(array $columns = ['*'], array $condition = [], int $perpage = 20, array $orderBy = [], array $extend = [], array $join = [], array $relations = [], array $rawQuery = []) {
        $query = $this->model->select($columns)->where(function($query) use ($condition) {
            if(isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'LIKE', '%'.$condition['keyword'].'%')
                        ->orWhere('email', 'LIKE', '%'.$condition['keyword'].'%')
                        ->orWhere('address', 'LIKE', '%'.$condition['keyword'].'%')
                        ->orWhere('phone', 'LIKE', '%'.$condition['keyword'].'%');
            }

            if(isset($condition['status']) && $condition['status'] != 0) {
                $query->where('status', '=', $condition['status']);
            }

            if(isset($condition['where']) && count($condition['where'])) {
                foreach($condition['where'] as $key => $value) {
                    $query->where($value[0], $value[1], $value[2],);
                }
            }
        })->with('user_catalogue');
        if(!empty($join)) {
            $query->join(...$join);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }
}

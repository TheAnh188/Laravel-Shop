<?php

namespace App\Repositories;

use App\Models\PostCatalogue;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function all(array $relation = [], $deleted_at = true, $language_id = 0) {
        return $this->model->with($relation)->get()->where($deleted_at ? 'deleted_at' : '', '=', NULL);
    }

    public function findById(string $id, array $columns = ['*'], array $relations = []) {
        return $this->model->select($columns)->with($relations)->findOrFail($id);
    }

    public function findByCondition(array $condition = []) {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $value) {
            $query->where($value[0],$value[1],$value[2]);
        }
        return $query->first();
    }

    public function create(array $payload = []) {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function update(int $id, array $payload = []) {
        $model = $this->findById($id);
        return $model->update($payload);
    }

    public function updateByWhereIN(string $whereInField = '', array $whereIn = [], array $payload = []) {
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }

    public function updateByWhere($condition = [], array $payload = []) {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $value) {
            $query->where($value[0],$value[1],$value[2]);
        }
        return $query->update($payload);
    }

    public function delete(int $id = 0) {
        return $this->findById($id)->deleteOrFail();
    }

    public function forceDelete(int $id = 0) {
        return $this->findById($id)->forceDelete();
    }

    public function forceDeleteByCondition(array $condition = []) {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $value) {
            $query->where($value[0],$value[1],$value[2]);
        }
        return $query->forceDelete();
    }

    public function deleteByCondition(array $condition = []) {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $value) {
            $query->where($value[0],$value[1],$value[2]);
        }
        return $query->delete();
    }

    public function paginate(array $columns = ['*'], array $condition = [], int $perpage = 20, array $orderBy = ['id', 'DESC'], array $extend = [], array $join = [], array $relations = [], array $rawQuery = []) {
        $query = $this->model->select($columns)->where(function($query) use ($condition) {
            // if(isset($condition['keyword']) && !empty($condition['keyword'])) {
            //     $query->where('name', 'LIKE', '%'.$condition['keyword'].'%');
            // }

            // if(isset($condition['status']) && $condition['status'] != 0) {
            //     $query->where('status', '=', $condition['status']);
            // }

            // if(isset($condition['where']) && count($condition['where'])) {
            //     foreach($condition['where'] as $key => $value) {
            //         $query->where($value[0], $value[1], $value[2],);
            //     }
            // }
        })
        ->keyword($condition['keyword'] ?? NULL)
            ->status($condition['status'] ?? NULL)
            ->customWhere($condition['where'] ?? NULL)
            ->customWhereRaw($rawQuery['whereRaw'] ?? NULL)
            ->customJoin($join ?? NULL)
            ->customGroupBy($extend['groupBy'] ?? NULL)
            ->customOrderBy($orderBy ?? NULL)
            ->customWithCount($relations ?? NULL)
            ->customWith($relations ?? NULL);


        // if(isset($rawQuery['whereRaw']) && count($rawQuery['whereRaw'])) {
        //     foreach($rawQuery['whereRaw'] as $key => $value) {
        //         $query->whereRaw($value[0], $value[1]);
        //     }
        // }

        // if(isset($relations) && !empty($relations)) {
        //     foreach($relations as $relation) {
        //         $query->withCount($relation);
        //     }
        // }

        // if(isset($join) && is_array($join) && count($join)) {
        //     // $query->join(...$join);
        //     foreach($join as $key => $value) {
        //         $query->join($value[0], $value[1], $value[2], $value[3]);
        //     }
        // }

        // if(isset($extend['groupBy']) && !empty($extend['groupBy'])) {
        //     $query->groupBy($extend['groupBy']);
        // }

        // if(isset($orderBy) && !empty($orderBy)) {
        //     // $query->orderBy(...$orderBy);
        //     $query->orderBy($orderBy[0], $orderBy[1]);
        // }

        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }

    public function createPivot($model, array $payload = [], string $relation = '') {
        return $model->{$relation}()->attach($model->id, $payload);
    }

}

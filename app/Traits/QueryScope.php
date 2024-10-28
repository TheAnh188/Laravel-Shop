<?php

namespace App\Traits;


trait QueryScope
{
    public function __construct() {

    }

    public function scopeKeyword($query, $keyword) {
        if(!empty($keyword)) {
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        }
        return $query;
    }

    public function scopeStatus($query, $status) {
        if(!empty($status) && $status != 0) {
            $query->where('status', '=', $status);
        }
        return $query;
    }

    public function scopeCustomWhere($query, $where = []) {
        if(!empty($where)) {
            foreach($where as $key => $value) {
                $query->where($value[0], $value[1], $value[2],);
            }
        }
        return $query;
    }

    public function scopeCustomWhereRaw($query, $rawQuery = []) {
        if(!empty($rawQuery)) {
            foreach($rawQuery as $key => $value) {
                $query->whereRaw($value[0], $value[1]);
            }
        }
        return $query;
    }

    public function scopeCustomWithCount($query, $relations) {
        if(!empty($relations)) {
            foreach($relations as $relation) {
                $query->withCount($relation);
            }
        }
        return $query;
    }

    public function scopeCustomWith($query, $relations) {
        if(!empty($relations)) {
            foreach($relations as $relation) {
                $query->with($relation);
            }
        }
        return $query;
    }

    public function scopeCustomJoin($query, $join) {
        if(isset($join) && is_array($join)) {
            // $query->join(...$join);
            foreach($join as $key => $value) {
                $query->join($value[0], $value[1], $value[2], $value[3]);
            }
        }
        return $query;
    }

    public function scopeCustomGroupBy($query, $extend) {
        if(isset($extend)) {
            $query->groupBy($extend);
        }
        return $query;
    }

    public function scopeCustomOrderBy($query, $orderBy) {
        if(isset($orderBy) && !empty($orderBy)) {
            // $query->orderBy(...$orderBy);
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query;
    }
}

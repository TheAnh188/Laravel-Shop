<?php

namespace App\Repositories;

use App\Models\{Module};
use App\Repositories\Interfaces\{Module}RepositoryInterface;

/**
 * Class {Module}Repository
 * @package App\Repositories
 */
class {Module}Repository extends BaseRepository implements {Module}RepositoryInterface
{

    protected $model;

    //Module = PostCatalogue
    //tableName = post_catalogue

    public function __construct({Module} $model) {
        $this->model = $model;
    }

    public function get{Module}ById(int $id = 0, $language_id = 0) {
        return $this->model->select([
                                        '{tableName}s.id',
                                        '{tableName}s.parent_id',
                                        '{tableName}s.image',
                                        '{tableName}s.icon',
                                        '{tableName}s.album',
                                        '{tableName}s.status',
                                        '{tableName}s.follow',
                                        'tb2.name',
                                        'tb2.description',
                                        'tb2.content',
                                        'tb2.meta_title',
                                        'tb2.meta_keyword',
                                        'tb2.meta_description',
                                        'tb2.canonical',
                                    ])
                            ->join('{tableName}_language as tb2', 'tb2.{tableName}_id', '=', '{tableName}s.id')
                            ->where('tb2.language_id', '=', $language_id)
                            ->find($id);
    }
}

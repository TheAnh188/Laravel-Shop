<?php

namespace App\Repositories;

use App\Models\System;
use App\Repositories\Interfaces\SystemRepositoryInterface;

/**
 * Class ProductRepository
 * @package App\Repositories
 */
class SystemRepository extends BaseRepository implements SystemRepositoryInterface
{

    protected $model;

    public function __construct(System $model) {
        $this->model = $model;
    }
}

<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RouteRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Route;

/**
 * Class RouteService
 * @package App\Services
 */
class RouteRepository extends BaseRepository implements RouteRepositoryInterface
{
    protected $model;

    public function __construct(Route $model) {
        $this->model = $model;
    }
}

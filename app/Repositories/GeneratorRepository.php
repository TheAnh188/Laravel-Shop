<?php

namespace App\Repositories;

use App\Repositories\Interfaces\GeneratorRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Generator;

/**
 * Class GeneratorService
 * @package App\Services
 */
class GeneratorRepository extends BaseRepository implements GeneratorRepositoryInterface
{
    protected $model;

    public function __construct(Generator $model) {
        $this->model = $model;
    }
}

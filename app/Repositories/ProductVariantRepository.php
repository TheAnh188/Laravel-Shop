<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;

/**
 * Class ProductRepository
 * @package App\Repositories
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{

    protected $model;

    public function __construct(ProductVariant $model) {
        $this->model = $model;
    }
}

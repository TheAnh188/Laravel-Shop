<?php

namespace App\Repositories;

use App\Models\ProductVariantAttribute;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface;

/**
 * Class ProductRepository
 * @package App\Repositories
 */
class ProductVariantAttributeRepository extends BaseRepository implements ProductVariantAttributeRepositoryInterface
{

    protected $model;

    public function __construct(ProductVariantAttribute $model) {
        $this->model = $model;
    }
}

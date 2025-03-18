<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeCatalogueRepositoryInterface
 * @package App\Services\Interfaces
 */
interface ProductVariantRepositoryInterface
{
    public function createPivot($model, array $payload = [], string $relation = '');
}

<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeCatalogueRepositoryInterface
 * @package App\Services\Interfaces
 */
interface ProductVariantAttributeRepositoryInterface
{
    public function createBatch(array $payload = []);

}

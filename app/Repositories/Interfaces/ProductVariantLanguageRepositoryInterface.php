<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeCatalogueRepositoryInterface
 * @package App\Services\Interfaces
 */
interface ProductVariantLanguageRepositoryInterface
{
    public function createBatch(array $payload = []);
}

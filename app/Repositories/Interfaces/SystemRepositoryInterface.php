<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeCatalogueRepositoryInterface
 * @package App\Services\Interfaces
 */
interface SystemRepositoryInterface
{
    public function all(array $relation = [], $deleted_at = true, $language_id = 0);

    public function updateOrInsert(array $payload = [], array $condition = []);
}

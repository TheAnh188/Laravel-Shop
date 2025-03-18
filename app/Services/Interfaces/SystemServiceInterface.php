<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;


/**
 * Interface SystemServiceInterface
 * @package App\Services\Interfaces
 */
interface SystemServiceInterface
{
    public function save(Request $request, $language_id);
}

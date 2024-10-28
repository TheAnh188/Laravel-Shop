<?php

namespace App\Services\Interfaces;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\TranslateRequest;


/**
 * Interface LanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface LanguageServiceInterface
{
    public function paginate($request);
    public function create(StoreLanguageRequest $request);
    public function update(int $id, UpdateLanguageRequest $request);
    public function destroy(int $id);
    public function setStatus($post);
    public function setStatusAll($post);
    public function changeLanguage($id);
    public function createTranslation(TranslateRequest $request, $option);
}

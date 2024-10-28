<?php

namespace App\Services;

use App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\TranslateRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class LanguageService
 * @package App\Services
 */
class LanguageService extends BaseService implements LanguageServiceInterface
{
    protected $languageRepository;
    protected $routeRepository;


    public function __construct(LanguageRepository $languageRepository, RouteRepository $routeRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->routeRepository = $routeRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['languages.deleted_at', '=', NULL],
        ];
        $perpage = $request->integer('perpage');

        $languages = $this->languageRepository->paginate(['id', 'name', 'canonical', 'status', 'image'], $condition, $perpage, [], ['path' => '/language'], [], [], []);
        return $languages;
    }

    private function encodeImageToBase64($request)
    {
        $base64String = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageData = file_get_contents($image);
            $base64Image = base64_encode($imageData);

            // Xác định định dạng ảnh
            $imageMimeType = $image->getMimeType(); // Ví dụ: image/jpeg, image/png
            $base64String = "data:$imageMimeType;base64,$base64Image";
        }
        return $base64String;
    }

    public function create(StoreLanguageRequest $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token']);
            $payload['user_id'] = Auth::id();
            $payload['image'] = $this->encodeImageToBase64($request);
            $this->languageRepository->create($payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdateLanguageRequest $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', '_method']);
            $payload['image'] = $this->encodeImageToBase64($request);
            $this->languageRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->languageRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatus($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = ($post['value'] == 1) ? 2 : 1;
            $this->languageRepository->update($post['modelId'], $payload);
            // $this->setUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatusAll($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $this->languageRepository->updateByWhereIN('id', $post['id'], $payload);
            // $this->setUserStatus($post, $post['value']);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function changeLanguage($id)
    {
        DB::beginTransaction();
        try {
            $payload = ['current' => 0];
            $where = [
                ['id', '!=', $id],
                ['deleted_at', '=', NULL],
            ];
            $language = $this->languageRepository->update($id, ['current' => 1]);
            $this->languageRepository->updateByWhere($where, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    private function convertModelToModelId($model)
    {
        //vd: post chuyen thanh post_id
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        return $temp . '_id';
    }

    public function createTranslation(TranslateRequest $request, $option)
    {
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $request->input('translated_name'),
                'description' => $request->input('translated_description'),
                'content' => $request->input('translated_content'),
                'meta_title' => $request->input('translated_meta_title'),
                'meta_keyword' => $request->input('translated_meta_keyword'),
                'meta_description' => $request->input('translated_meta_description'),
                'canonical' => $request->input('translated_canonical'),
                $this->convertModelToModelId($option['model_name']) => $option['id'],
                'language_id' => $option['language_id'],
            ];
            $controllerName = $option['model_name'].'Controller';
            //Lay repository tuong ung voi ten model
            $repositoryInterfaceNamespace = '\App\Repositories\\' . ucfirst($option['model_name']) . 'Repository';
            if (class_exists($repositoryInterfaceNamespace)) {
                $repositoryInstance = app($repositoryInterfaceNamespace);
            }

            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach([$option['language_id'], $model->id]);
            $repositoryInstance->createPivot($model, $payload, 'languages');
            //Xoa cac duong dan da duoc dich neu co sau do insert lai ban dich moi
            $this->routeRepository->forceDeleteByCondition([
                ['module_id', '=', $option['id']],
                ['controller', '=', 'App\Http\Controllers\Frontend\\'.$controllerName],
                ['language_id', '=', $option['language_id']],
            ]);
            $this->routeRepository->create(
                $this->formatRoutePayload(
                    $request->input('translated_canonical'),
                    $option['id'],
                    $controllerName,
                    $option['language_id'],
                )
            );
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

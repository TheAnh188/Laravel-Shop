<?php

namespace App\Services;

use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
// use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository ;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeCatalogueService extends BaseService implements AttributeCatalogueServiceInterface
{
    protected $attributeCatalogueRepository;
    protected $routeRepository;
    protected $nestedset;
    private $controller = 'AttributeCatalogueController';

    public function __construct(AttributeCatalogueRepository $attributeCatalogueRepository, RouteRepository $routeRepository)
    {
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->routeRepository = $routeRepository;
    }

    public function paginate($request, $language_id)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['tb2.language_id', '=', $language_id],
            ['attribute_catalogues.deleted_at', '=', NULL],
        ];

        $perpage = $request->integer('perpage');

        $attributeCatalogues = $this->attributeCatalogueRepository->paginate(
            ['attribute_catalogues.id', 'attribute_catalogues.status', 'attribute_catalogues.image', 'attribute_catalogues.level', 'attribute_catalogues.order', 'tb2.name', 'tb2.canonical'],
            $condition,
            $perpage,
            [
                'attribute_catalogues.lft',
                'ASC',
            ],
            ['path' => '/attribute-catalogue'],
            [
                [
                    'attribute_catalogue_language as tb2',
                    'tb2.attribute_catalogue_id',
                    '=',
                    'attribute_catalogues.id',
                ],
            ],
            [],
            [],

        );
        return $attributeCatalogues;
    }

    private function formatAttributeCataloguePayload($request)
    {
        $attributeCataloguePayload = $request->only(['parent_id', 'follow', 'status', 'image', 'album']);
        $attributeCataloguePayload['album'] = isset($attributeCataloguePayload['album']) && !empty($attributeCataloguePayload['album']) ? json_encode($attributeCataloguePayload['album']) : NULL;
        $attributeCataloguePayload['user_id'] = Auth::id();
        return $attributeCataloguePayload;
    }

    private function formatAttributeCatalogueLanguagePayload($request, $attributeCatalogueId, $language_id)
    {
        $attributeCatalogueLanguagePayload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'canonical']);
        $attributeCatalogueLanguagePayload['canonical'] = str_replace(env('APP_URL'), '', $attributeCatalogueLanguagePayload['canonical']);
        $attributeCatalogueLanguagePayload['language_id'] = $language_id;
        $attributeCatalogueLanguagePayload['attribute_catalogue_id'] = $attributeCatalogueId;
        return $attributeCatalogueLanguagePayload;
    }

    public function create(StoreattributeCatalogueRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $attributeCatalogue = $this->attributeCatalogueRepository->create($this->formatAttributeCataloguePayload($request));
            if ($attributeCatalogue->id > 0) {
                $this->attributeCatalogueRepository->createPivot($attributeCatalogue, $this->formatAttributeCatalogueLanguagePayload($request, $attributeCatalogue->id, $language_id), 'languages');
                $this->routeRepository->create(
                    $this->formatRoutePayload(
                        $this->formatAttributeCatalogueLanguagePayload($request, $attributeCatalogue->id, $language_id)['canonical'],
                        $attributeCatalogue->id,
                        $this->controller,
                        $language_id,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'attribute_catalogues',
                    'foreignkey' => 'attribute_catalogue_id',
                    'language_id' => $language_id,
                ]);
            }
            $this->nestedSet($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdateattributeCatalogueRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $result = $this->attributeCatalogueRepository->update($id, $this->formatAttributeCataloguePayload($request));
            if ($result == true) {
                $attributeCatalogue->languages()->detach([$this->formatAttributeCatalogueLanguagePayload($request, $attributeCatalogue->id, $language_id)['language_id'], $id]);
                $this->attributeCatalogueRepository->createPivot($attributeCatalogue, $this->formatAttributeCatalogueLanguagePayload($request, $attributeCatalogue->id, $language_id), 'languages');
                $condition = [
                    ['module_id', '=', $id],
                    ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller],
                ];
                $route = $this->routeRepository->findByCondition($condition);
                $this->routeRepository->update(
                    $route->id,
                    $this->formatRoutePayload(
                        $this->formatAttributeCatalogueLanguagePayload($request, $attributeCatalogue->id, $language_id)['canonical'],
                        $attributeCatalogue->id,
                        $this->controller,
                        $language_id,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'attribute_catalogues',
                    'foreignkey' => 'attribute_catalogue_id',
                    'language_id' => $language_id,
                ]);
                $this->nestedSet($this->nestedset);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy(int $id, $language_id)
    {
        DB::beginTransaction();
        try {
            $this->attributeCatalogueRepository->delete($id);
            $this->routeRepository->deleteByCondition([
                ['module_id', '=', $id],
                ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller]
            ]);
            $this->nestedset = new Nestedsetbie([
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
                'language_id' => $language_id,
            ]);
            $this->nestedSet($this->nestedset);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatus($attribute)
    {
        DB::beginTransaction();
        try {
            $payload[$attribute['field']] = ($attribute['value'] == 1) ? 2 : 1;
            $this->attributeCatalogueRepository->update($attribute['modelId'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatusAll($attribute)
    {
        DB::beginTransaction();
        try {
            $payload[$attribute['field']] = $attribute['value'];
            $this->attributeCatalogueRepository->updateByWhereIN('id', $attribute['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

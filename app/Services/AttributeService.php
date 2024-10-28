<?php

namespace App\Services;

use App\Services\Interfaces\AttributeServiceInterface;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
// use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository ;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class AttributeService
 * @package App\Services
 */
class AttributeService extends BaseService implements AttributeServiceInterface
{
    protected $attributeRepository;
    protected $routeRepository;
    private $controller = 'AttributeController';
    // protected $attributeRepository;

    public function __construct(AttributeRepository $attributeRepository, RouteRepository $routeRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->routeRepository = $routeRepository;
    }

    private function whereRaw($request, $language_id)
    {
        $rawCondition = [];
        if ($request->integer('attribute_catalogue_id') > 0) {
            //cau lenh truy van tim kiem bai viet thuoc loai danh muc nao
            //(truy van bong da thi se hien thi luon ca  luon ca bong da quoc te va bong da trong nuoc)
            $rawCondition['whereRaw'] =  [
                [
                    'tb3.attribute_catalogue_id IN (
                        SELECT id
                        FROM attribute_catalogues
                        JOIN attribute_catalogue_language ON attribute_catalogues.id = attribute_catalogue_language.attribute_catalogue_id
                        WHERE lft >= (SELECT lft FROM attribute_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM attribute_catalogues as pc WHERE pc.id = ?)
                        AND attribute_catalogue_language.language_id = '.$language_id.'
                    )',
                    [$request->integer('attribute_catalogue_id'), $request->integer('attribute_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }

    public function paginate($request, $language_id)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['tb2.language_id', '=', $language_id],
            ['attributes.deleted_at', '=', NULL],
        ];

        $perpage = $request->integer('perpage');

        $attributes = $this->attributeRepository->paginate(
            ['attributes.id', 'attributes.status', 'attributes.image', 'attributes.order', 'tb2.name', 'tb2.canonical'],
            $condition,
            $perpage,
            [
                'attributes.id',
                'DESC',
            ],
            [
                'path' => '/attribute',
                'groupBy' => ['attributes.id', 'attributes.status', 'attributes.image', 'attributes.order', 'tb2.name', 'tb2.canonical'],
            ],
            [
                [
                    'attribute_language as tb2',
                    'tb2.attribute_id',
                    '=',
                    'attributes.id',
                ],
                [
                    'attribute_catalogue_attribute as tb3',
                    'attributes.id',
                    '=',
                    'tb3.attribute_id',
                ]
            ],
            ['attribute_catalogues'],
            $this->whereRaw($request, $language_id),

        );
        // dd($attributes);die();
        return $attributes;
    }

    private function formatAttributePayload($request)
    {
        $attributePayload = $request->only(['follow', 'status', 'image', 'album', 'attribute_catalogue_id']);
        $attributePayload['album'] = isset($attributePayload['album']) && !empty($attributePayload['album']) ? json_encode($attributePayload['album']) : NULL;
        $attributePayload['user_id'] = Auth::id();
        return $attributePayload;
    }

    private function formatAttributeLanguagePayload($request, $attributeId, $language_id)
    {
        $attributeLanguagePayload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'canonical']);
        $attributeLanguagePayload['canonical'] = str_replace(env('APP_URL'), '', $attributeLanguagePayload['canonical']);
        $attributeLanguagePayload['language_id'] = $language_id;
        $attributeLanguagePayload['attribute_id'] = $attributeId;
        return $attributeLanguagePayload;
    }

    private function formatAttributeCatalogueAttributePayload($request)
    {
        $attributeCatalogueAttributePayload = array_unique(array_merge($request->input('catalogue'), [$request->input('attribute_catalogue_id')]));
        return $attributeCatalogueAttributePayload;
    }

    public function create(StoreAttributeRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $attribute = $this->attributeRepository->create($this->formatAttributePayload($request));
            if ($attribute->id > 0) {
                $this->attributeRepository->createPivot($attribute, $this->formatAttributeLanguagePayload($request, $attribute->id, $language_id), 'languages');
                $attribute->attribute_catalogues()->sync($this->formatAttributeCatalogueAttributePayload($request));
                $this->routeRepository->create(
                    $this->formatRoutePayload(
                        $this->formatAttributeLanguagePayload($request, $attribute->id, $language_id)['canonical'],
                        $attribute->id,
                        $this->controller,
                        $language_id,
                    )
                );
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdateAttributeRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $attribute = $this->attributeRepository->findById($id);
            $result = $this->attributeRepository->update($id, $this->formatAttributePayload($request));
            if ($result == true) {
                $attribute->languages()->detach([$this->formatAttributeLanguagePayload($request, $attribute->id, $language_id)['language_id'], $id]);
                $this->attributeRepository->createPivot($attribute, $this->formatAttributeLanguagePayload($request, $attribute->id, $language_id), 'languages');
                $attribute->attribute_catalogues()->sync($this->formatAttributeCatalogueAttributePayload($request));
                $condition = [
                    ['module_id', '=', $id],
                    ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller],
                ];
                $route = $this->routeRepository->findByCondition($condition);
                // dd($route);die();
                $this->routeRepository->update(
                    $route->id,
                    $this->formatRoutePayload(
                        $this->formatAttributeLanguagePayload($request, $attribute->id, $language_id)['canonical'],
                        $attribute->id,
                        $this->controller,
                        $language_id,
                    )
                );

            }
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
        // DB::beginTransaction();
        // try {
            $this->attributeRepository->delete($id);
            $this->routeRepository->deleteByCondition([
                ['module_id', '=', $id],
                ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller]
            ]);
            // DB::commit();
            return true;
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     Log::error($e->getMessage());
        //     return false;
        // }
    }

    public function setStatus($attribute)
    {
        DB::beginTransaction();
        try {
            $payload[$attribute['field']] = ($attribute['value'] == 1) ? 2 : 1;
            $this->attributeRepository->update($attribute['modelId'], $payload);
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
            $this->attributeRepository->updateByWhereIN('id', $attribute['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

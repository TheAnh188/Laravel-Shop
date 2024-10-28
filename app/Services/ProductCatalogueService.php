<?php

namespace App\Services;

use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductCatalogueRequest;
use App\Http\Requests\UpdateProductCatalogueRequest;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductCatalogueService extends BaseService implements ProductCatalogueServiceInterface
{
    protected $productCatalogueRepository;
    protected $routeRepository;
    protected $nestedset;
    private $controller = 'ProductCatalogueController';

    //Module = PostCatalogue
    //module = postCatalogue
    //tableName = post_catalogue
    //moduleCanonical = post-catalogue


    public function __construct(ProductCatalogueRepository $productCatalogueRepository, RouteRepository $routeRepository)
    {
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->routeRepository = $routeRepository;
    }

    public function paginate($request, $language_id)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['tb2.language_id', '=', $language_id],
            ['product_catalogues.deleted_at', '=', NULL],
        ];

        $perpage = $request->integer('perpage');

        $productCatalogues = $this->productCatalogueRepository->paginate(
            ['product_catalogues.id', 'product_catalogues.status', 'product_catalogues.image', 'product_catalogues.level', 'product_catalogues.order', 'tb2.name', 'tb2.canonical'],
            $condition,
            $perpage,
            [
                'product_catalogues.lft',
                'ASC',
            ],
            ['path' => '/product-catalogue'],
            [
                [
                    'product_catalogue_language as tb2',
                    'tb2.product_catalogue_id',
                    '=',
                    'product_catalogues.id',
                ],
            ],
            [],
            [],

        );
        return $productCatalogues;
    }

    private function formatProductCataloguePayload($request)
    {
        $productCataloguePayload = $request->only(['parent_id', 'follow', 'status', 'image', 'album']);
        $productCataloguePayload['album'] = isset($productCataloguePayload['album']) && !empty($productCataloguePayload['album']) ? json_encode($productCataloguePayload['album']) : NULL;
        $productCataloguePayload['user_id'] = Auth::id();
        return $productCataloguePayload;
    }

    private function formatProductCatalogueLanguagePayload($request, $productCatalogueId, $language_id)
    {
        $productCatalogueLanguagePayload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'canonical']);
        $productCatalogueLanguagePayload['canonical'] = str_replace(env('APP_URL'), '', $productCatalogueLanguagePayload['canonical']);
        $productCatalogueLanguagePayload['language_id'] = $language_id;
        $productCatalogueLanguagePayload['product_catalogue_id'] = $productCatalogueId;
        return $productCatalogueLanguagePayload;
    }

    public function create(StoreProductCatalogueRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $productCatalogue = $this->productCatalogueRepository->create($this->formatProductCataloguePayload($request));
            if ($productCatalogue->id > 0) {
                $this->productCatalogueRepository->createPivot($productCatalogue, $this->formatProductCatalogueLanguagePayload($request, $productCatalogue->id, $language_id), 'languages');
                $this->routeRepository->create(
                    $this->formatRoutePayload(
                        $this->formatProductCatalogueLanguagePayload($request, $productCatalogue->id, $language_id)['canonical'],
                        $productCatalogue->id,
                        $this->controller,
                        $language_id,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'product_catalogues',
                    'foreignkey' => 'product_catalogue_id',
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

    public function update(int $id, UpdateProductCatalogueRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            $result = $this->productCatalogueRepository->update($id, $this->formatProductCataloguePayload($request));
            if ($result == true) {
                $productCatalogue->languages()->detach([$this->formatProductCatalogueLanguagePayload($request, $productCatalogue->id, $language_id)['language_id'], $id]);
                $this->productCatalogueRepository->createPivot($productCatalogue, $this->formatProductCatalogueLanguagePayload($request, $productCatalogue->id, $language_id), 'languages');
                $condition = [
                    ['module_id', '=', $id],
                    ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller],
                ];
                $route = $this->routeRepository->findByCondition($condition);
                $this->routeRepository->update(
                    $route->id,
                    $this->formatRoutePayload(
                        $this->formatProductCatalogueLanguagePayload($request, $productCatalogue->id, $language_id)['canonical'],
                        $productCatalogue->id,
                        $this->controller,
                        $language_id,
                    )
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'product_catalogues',
                    'foreignkey' => 'product_catalogue_id',
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
            $this->productCatalogueRepository->delete($id);
            $this->routeRepository->deleteByCondition([
                ['module_id', '=', $id],
                ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller]
            ]);
            $this->nestedset = new Nestedsetbie([
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
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

    public function setStatus($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = ($post['value'] == 1) ? 2 : 1;
            $this->productCatalogueRepository->update($post['modelId'], $payload);
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
            $this->productCatalogueRepository->updateByWhereIN('id', $post['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Interfaces\ProductServiceInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\RouteRepositoryInterface as RouteRepository;
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface as ProductVariantLanguageRepository ;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface as ProductVariantAttributeRepository ;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService extends BaseService implements ProductServiceInterface
{
    protected $productRepository;
    protected $routeRepository;
    private $controller = 'ProductController';
    protected $productVariantLanguageRepository;
    protected $productVariantAttributeRepository;

    public function __construct(ProductRepository $productRepository, RouteRepository $routeRepository, ProductVariantLanguageRepository $productVariantLanguageRepository, ProductVariantAttributeRepository $productVariantAttributeRepository)
    {
        $this->productRepository = $productRepository;
        $this->routeRepository = $routeRepository;
        $this->productVariantLanguageRepository = $productVariantLanguageRepository;
        $this->productVariantAttributeRepository = $productVariantAttributeRepository;
    }

    private function whereRaw($request, $language_id)
    {
        $rawCondition = [];
        if ($request->integer('product_catalogue_id') > 0) {
            //cau lenh truy van tim kiem bai viet thuoc loai danh muc nao
            //(truy van bong da thi se hien thi luon ca  luon ca bong da quoc te va bong da trong nuoc)
            $rawCondition['whereRaw'] =  [
                [
                    'tb3.product_catalogue_id IN (
                        SELECT id
                        FROM product_catalogues
                        JOIN product_catalogue_language ON product_catalogues.id = product_catalogue_language.product_catalogue_id
                        WHERE lft >= (SELECT lft FROM product_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM product_catalogues as pc WHERE pc.id = ?)
                        AND product_catalogue_language.language_id = '.$language_id.'
                    )',
                    [$request->integer('product_catalogue_id'), $request->integer('product_catalogue_id')]
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
            ['products.deleted_at', '=', NULL],
        ];

        $perpage = $request->integer('perpage');

        $products = $this->productRepository->paginate(
            ['products.id', 'products.status', 'products.image', 'products.order', 'tb2.name', 'tb2.canonical'],
            $condition,
            $perpage,
            [
                'products.id',
                'DESC',
            ],
            [
                'path' => '/product',
                'groupBy' => ['products.id', 'products.status', 'products.image', 'products.order', 'tb2.name', 'tb2.canonical'],
            ],
            [
                [
                    'product_language as tb2',
                    'tb2.product_id',
                    '=',
                    'products.id',
                ],
                [
                    'product_catalogue_product as tb3',
                    'products.id',
                    '=',
                    'tb3.product_id',
                ]
            ],
            ['product_catalogues'],
            $this->whereRaw($request, $language_id),

        );
        // dd($products);die();
        return $products;
    }

    private function formatProductPayload($request)
    {
        $productPayload = $request->only(['follow', 'status', 'image', 'album', 'product_catalogue_id', 'price', 'code', 'made_in', 'attributeCatalogue', 'attribute', 'variant']);
        $productPayload['album'] = isset($productPayload['album']) && !empty($productPayload['album']) ? json_encode($productPayload['album']) : NULL;
        $productPayload['user_id'] = Auth::id();
        $productPayload['price'] = str_replace('.', '', $productPayload['price']);
        $productPayload['attributeCatalogue'] = isset($productPayload['attributeCatalogue']) && !empty($productPayload['attributeCatalogue']) ? json_encode($productPayload['attributeCatalogue']) : NULL;
        $productPayload['attribute_catalogue'] = $productPayload['attributeCatalogue'];
        $productPayload['attribute'] = isset($productPayload['attribute']) && !empty($productPayload['attribute']) ? json_encode($productPayload['attribute']) : NULL;
        $productPayload['attributes'] = $productPayload['attribute'];
        $productPayload['variant'] = isset($productPayload['variant']) && !empty($productPayload['variant']) ? json_encode($productPayload['variant']) : NULL;
        $productPayload['variants'] = $productPayload['variant'];
        unset($productPayload['attributeCatalogue']);
        unset($productPayload['attribute']);
        // dd( $productPayload);
        return $productPayload;
    }

    private function formatProductLanguagePayload($request, $productId, $language_id)
    {
        $productLanguagePayload = $request->only(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'canonical']);
        $productLanguagePayload['canonical'] = str_replace(env('APP_URL'), '', $productLanguagePayload['canonical']);
        $productLanguagePayload['language_id'] = $language_id;
        $productLanguagePayload['product_id'] = $productId;
        return $productLanguagePayload;
    }

    private function formatProductCatalogueProductPayload($request)
    {
        $productCatalogueProductPayload = array_unique(array_merge($request->input('catalogue'), [$request->input('product_catalogue_id')]));
        return $productCatalogueProductPayload;
    }

    private function createVariant($request, $product, $language_id)
    {
        $payload = $request->only(['variant', 'attribute_input', 'attribute']);
        $variants = $this->formatVariantPayload($payload);
        $product->product_variants()->delete();//xóa tất cả nhưng biến thể của san phẩm trong csdl
        $variants = $product->product_variants()->createMany($variants);//thêm nhiều biến thể của sản phẩm thông qua qh product_variants
        $variantIds = $variants->pluck('id');
        $productVariantLanguagePayload = [];
        $productVariantAttributePayload = [];
        $combinedAttributes = $this->combineAttribute(array_values($payload['attribute']));
        if(count($variantIds)) {
            foreach($variantIds as $key => $value){
                $productVariantLanguagePayload[] = [
                    'product_variant_id' => $value,
                    'language_id' => $language_id,
                    'name' => $payload['attribute_input']['name'][$key],
                ];
                foreach($combinedAttributes[$key] as $index) {
                    $productVariantAttributePayload[] = [
                        'product_variant_id' => $value,
                        'attribute_id' => $index,
                    ];
                }
            }

        }
        // dd($productVariantAttributePayload);
        //tạo bản ghi vào bảng product_variant_language
        $this->productVariantLanguageRepository->createBatch($productVariantLanguagePayload);
        //tạo bản ghi vào bảng product_variant_attribute
        $this->productVariantAttributeRepository->createBatch($productVariantAttributePayload);
    }

    private function combineAttribute($attributes = [], $index = 0) {
        if($index === count($attributes)) return[[]];
        $repetition = $this->combineAttribute($attributes, $index + 1);
        $formattedAttributePayload = [];
        foreach ($attributes[$index] as $key => $value) {
            foreach ($repetition as $keyy => $valuee) {
                $formattedAttributePayload[] = array_merge([$value], $valuee);
            }
        }
        return $formattedAttributePayload;
    }

    private function formatVariantPayload($payload = []):array
    {
        $variants = [];
        if(isset($payload['variant']['sku'])){
            foreach($payload['variant']['sku'] as $key => $value){
                $variants[] = [
                    'code' => $payload['attribute_input']['id'][$key] ?? NULL,
                    'quantity' => $payload['variant']['quantity'][$key] ?? NULL,
                    'sku' => $value ?? NULL,
                    'price' => str_replace('.', '', $payload['variant']['price'][$key]) ?? NULL,
                    'barcode' => $payload['variant']['barcode'][$key] ?? NULL,
                    'filename' => $payload['variant']['filename'][$key] ?? NULL,
                    'file_url' => $payload['variant']['file_url'][$key] ?? NULL,
                    'album' => $payload['variant']['album'][$key] ?? NULL,
                    'user_id' => Auth::id(),
                ];
            }
        }
        return $variants;
    }

    public function create(StoreProductRequest $request, $language_id)
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->create($this->formatProductPayload($request));
            if ($product->id > 0) {
                $this->productRepository->createPivot($product, $this->formatProductLanguagePayload($request, $product->id, $language_id), 'languages');
                $product->product_catalogues()->sync($this->formatProductCatalogueProductPayload($request));
                $this->routeRepository->create(
                    $this->formatRoutePayload(
                        $this->formatProductLanguagePayload($request, $product->id, $language_id)['canonical'],
                        $product->id,
                        $this->controller,
                        $language_id,
                    )
                );
                $this->createVariant($request, $product, $language_id);
            }
            DB::commit();
            // die();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdateProductRequest $request, $language_id)
    {
        DB::beginTransaction();
        // try {
            $product = $this->productRepository->findById($id);
            $result = $this->productRepository->update($id, $this->formatProductPayload($request));
            if ($result == true) {
                $product->languages()->detach([$this->formatProductLanguagePayload($request, $product->id, $language_id)['language_id'], $id]);
                $this->productRepository->createPivot($product, $this->formatProductLanguagePayload($request, $product->id, $language_id), 'languages');
                $product->product_catalogues()->sync($this->formatProductCatalogueProductPayload($request));
                $condition = [
                    ['module_id', '=', $id],
                    ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller],
                ];
                $route = $this->routeRepository->findByCondition($condition);
                // dd($route);die();
                $this->routeRepository->update(
                    $route->id,
                    $this->formatRoutePayload(
                        $this->formatProductLanguagePayload($request, $product->id, $language_id)['canonical'],
                        $product->id,
                        $this->controller,
                        $language_id,
                    )
                );
                //xoa phiên bản trước khi cập nhật
                $product->product_variants()->each(function($variant) {
                    $variant->languages()->detach();
                    $variant->attributes()->detach();
                    $variant->delete();
                });
                $this->createVariant($request, $product, $language_id);

            }
            DB::commit();
            return true;
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     echo $e->getMessage();
        //     return false;
        // }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->productRepository->delete($id);
            $this->routeRepository->deleteByCondition([
                ['module_id', '=', $id],
                ['controller', '=', 'App\Http\Controllers\Frontend\\' . $this->controller]
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function setStatus($product)
    {
        DB::beginTransaction();
        try {
            $payload[$product['field']] = ($product['value'] == 1) ? 2 : 1;
            $this->productRepository->update($product['modelId'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatusAll($product)
    {
        DB::beginTransaction();
        try {
            $payload[$product['field']] = $product['value'];
            $this->productRepository->updateByWhereIN('id', $product['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\Gate;
use Nette\Utils\Arrays;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(ProductService $productService, ProductRepository $productRepository, AttributeCatalogueRepository $attributeCatalogueRepository){
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->initialize();
    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request) {
        $canonicalPermission = 'product';
        $dropdown = $this->nestedset->Dropdown();
        $products = $this->productService->paginate($request, $this->language);
        $model = 'product';
        return view("product/index", [
            'dropdown' => $dropdown,
            'products' => $products,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'product/create';
        $attributeCatalogues = $this->attributeCatalogueRepository->all([], true, $this->language);
        $dropdown = $this->nestedset->Dropdown();
        return view("product/create", [
            'dropdown' => $dropdown,
            'authorization' => $this->customAuthorize($canonicalPermission),
            'attribute_catalogues' => $attributeCatalogues,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        if($this->productService->create($request, $this->language)) {
            return redirect('/product')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/product')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'product/edit';
        $dropdown = $this->nestedset->Dropdown();
        $product = $this->productRepository->getProductById($id, $this->language);
        $album = json_decode($product->album);
        return view("product/edit", [
            'dropdown' => $dropdown,
            'product' => $product,
            'album' => $album,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        if($this->productService->update($id, $request, $this->language)) {
            return redirect('/product')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/product')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'product/delete';
        $productCatalogue = $this->productRepository->getProductById($id, $this->language);
        return view("product/delete", [
            'product'=> $productCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->productService->destroy($id)) {
            return redirect('/product')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/product')->with('error', 'Đã xảy ra lỗi.');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Http\Requests\StoreProductCatalogueRequest;
use App\Http\Requests\UpdateProductCatalogueRequest;
use App\Http\Requests\DeleteProductCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Models\Language;

class ProductCatalogueController extends Controller
{
    protected $productCatalogueService;
    protected $productCatalogueRepository;
    protected $nestedset;

    //Dau vao PostCatalogue
    //moduleCanonical = post-catalogue
    //moduleView = post_catalogue
    //moduleTemplate = postCatalogue
    //ModuleTemplate = PostCatalogue
    //tableName = post_catalogues
    //foreignKey

    public function __construct(ProductCatalogueService $productCatalogueService, ProductCatalogueRepository $productCatalogueRepository){
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productCatalogueService = $productCatalogueService;
        $this->productCatalogueRepository = $productCatalogueRepository;

    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request) {
        $canonicalPermission = 'product-catalogue';
        $productCatalogues = $this->productCatalogueService->paginate($request, $this->language);
        $model = 'productCatalogue';
        return view("product_catalogue/index", [
            'product_catalogues'=> $productCatalogues,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'product-catalogue/create';
        $dropdown = $this->nestedset->Dropdown();
        return view("product_catalogue/create", [
            'dropdown'=> $dropdown,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StoreProductCatalogueRequest $request)
    {
        if($this->productCatalogueService->create($request, $this->language)) {
            return redirect('/product-catalogue')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/product-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'product-catalogue/edit';
        $dropdown = $this->nestedset->Dropdown();
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        $album = json_decode($productCatalogue->album);
        return view("product_catalogue/edit", [
            'dropdown' => $dropdown,
            'product_catalogue' => $productCatalogue,
            'album' => $album,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateProductCatalogueRequest $request, string $id)
    {
        if($this->productCatalogueService->update($id, $request, $this->language)) {
            return redirect('/product-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/product-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'product-catalogue/delete';
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        return view("product_catalogue/delete", [
            'product_catalogue'=> $productCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(DeleteProductCatalogueRequest $request, string $id)
    {
        if($this->productCatalogueService->destroy($id, $this->language)) {
            return redirect('/product-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/product-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }
}

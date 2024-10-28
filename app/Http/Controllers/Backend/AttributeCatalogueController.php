<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeCatalogueServiceInterface as AttributeCatalogueService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;
use App\Http\Requests\DeleteAttributeCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Models\Language;

class AttributeCatalogueController extends Controller
{
    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;
    protected $nestedset;

    //Dau vao PostCatalogue
    //moduleCanonical = post-catalogue
    //moduleView = post_catalogue
    //moduleTemplate = postCatalogue
    //ModuleTemplate = PostCatalogue
    //tableName = post_catalogues
    //foreignKey

    public function __construct(AttributeCatalogueService $attributeCatalogueService, AttributeCatalogueRepository $attributeCatalogueRepository){
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;

    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request) {
        $canonicalPermission = 'attribute-catalogue';
        $attributeCatalogues = $this->attributeCatalogueService->paginate($request, $this->language);
        $model = 'attributeCatalogue';
        return view("attribute_catalogue/index", [
            'attribute_catalogues'=> $attributeCatalogues,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'attribute-catalogue/create';
        $dropdown = $this->nestedset->Dropdown();
        return view("attribute_catalogue/create", [
            'dropdown'=> $dropdown,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StoreAttributeCatalogueRequest $request)
    {
        if($this->attributeCatalogueService->create($request, $this->language)) {
            return redirect('/attribute-catalogue')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/attribute-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'attribute-catalogue/edit';
        $dropdown = $this->nestedset->Dropdown();
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $album = json_decode($attributeCatalogue->album);
        return view("attribute_catalogue/edit", [
            'dropdown' => $dropdown,
            'attribute_catalogue' => $attributeCatalogue,
            'album' => $album,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateAttributeCatalogueRequest $request, string $id)
    {
        if($this->attributeCatalogueService->update($id, $request, $this->language)) {
            return redirect('/attribute-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/attribute-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'attribute-catalogue/delete';
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        return view("attribute_catalogue/delete", [
            'attribute_catalogue'=> $attributeCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(DeleteAttributeCatalogueRequest $request, string $id)
    {
        if($this->attributeCatalogueService->destroy($id, $this->language)) {
            return redirect('/attribute-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/attribute-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }
}

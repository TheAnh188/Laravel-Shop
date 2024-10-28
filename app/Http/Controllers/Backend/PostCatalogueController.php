<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Models\Language;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(PostCatalogueService $postCatalogueService, PostCatalogueRepository $postCatalogueRepository){
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->initialize();

    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request) {
        $canonicalPermission = 'post-catalogue';
        $postCatalogues = $this->postCatalogueService->paginate($request, $this->language);
        $model = 'postCatalogue';
        return view("post_catalogue/index", [
            'post_catalogues'=> $postCatalogues,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'post-catalogue/create';
        $dropdown = $this->nestedset->Dropdown();
        return view("post_catalogue/create", [
            'dropdown'=> $dropdown,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StorePostCatalogueRequest $request)
    {
        if($this->postCatalogueService->create($request, $this->language)) {
            return redirect('/post-catalogue')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/post-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'post-catalogue/edit';
        $dropdown = $this->nestedset->Dropdown();
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $album = json_decode($postCatalogue->album);
        return view("post_catalogue/edit", [
            'dropdown' => $dropdown,
            'post_catalogue' => $postCatalogue,
            'album' => $album,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdatePostCatalogueRequest $request, string $id)
    {
        if($this->postCatalogueService->update($id, $request, $this->language)) {
            return redirect('/post-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/post-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'post-catalogue/delete';
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        return view("post_catalogue/delete", [
            'post_catalogue'=> $postCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(DeletePostCatalogueRequest $request, string $id)
    {
        if($this->postCatalogueService->destroy($id, $this->language)) {
            return redirect('/post-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/post-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }
}

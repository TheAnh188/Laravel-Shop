<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeServiceInterface as AttributeService;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\Gate;



class AttributeController extends Controller
{
    protected $attributeService;
    protected $attributeRepository;
    protected $nestedset;
    protected $language;


    public function __construct(AttributeService $attributeService, AttributeRepository $attributeRepository){
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->attributeService = $attributeService;
        $this->attributeRepository = $attributeRepository;
        $this->initialize();
    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request) {
        $canonicalPermission = 'attribute';
        $dropdown = $this->nestedset->Dropdown();
        $attributes = $this->attributeService->paginate($request, $this->language);
        $model = 'attribute';
        return view("attribute/index", [
            'dropdown' => $dropdown,
            'attributes' => $attributes,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'attribute/create';
        $dropdown = $this->nestedset->Dropdown();
        return view("attribute/create", [
            'dropdown' => $dropdown,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StoreAttributeRequest $request)
    {
        if($this->attributeService->create($request, $this->language)) {
            return redirect('/attribute')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/attribute')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'attribute/edit';
        $dropdown = $this->nestedset->Dropdown();
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $album = json_decode($attribute->album);
        return view("attribute/edit", [
            'dropdown' => $dropdown,
            'attribute' => $attribute,
            'album' => $album,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateAttributeRequest $request, string $id)
    {
        if($this->attributeService->update($id, $request, $this->language)) {
            return redirect('/attribute')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/attribute')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'attribute/delete';
        $attributeCatalogue = $this->attributeRepository->getAttributeById($id, $this->language);
        return view("attribute/delete", [
            'attribute'=> $attributeCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->attributeService->destroy($id)) {
            return redirect('/attribute')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/attribute')->with('error', 'Đã xảy ra lỗi.');
    }
}

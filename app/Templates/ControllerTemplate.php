<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {ModuleTemplate}Service;
use App\Repositories\Interfaces\{ModuleTemplate}RepositoryInterface as {ModuleTemplate}Repository;
use App\Http\Requests\Store{ModuleTemplate}Request;
use App\Http\Requests\Update{ModuleTemplate}Request;
use App\Http\Requests\Delete{ModuleTemplate}Request;
use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\Gate;



class {ModuleTemplate}Controller extends Controller
{
    protected ${moduleTemplate}Service;
    protected ${moduleTemplate}Repository;
    protected $nestedset;
    protected $language;

    //Dau vao Post
    //moduleCanonical = post
    //moduleView = post
    //moduleTemplate = post
    //ModuleTemplate = Post
    //tableName = post_catalogues
    //foreignKey = post_catalogue_id

    public function __construct({ModuleTemplate}Service ${moduleTemplate}Service, {ModuleTemplate}Repository ${moduleTemplate}Repository){
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->{moduleTemplate}Service = ${moduleTemplate}Service;
        $this->{moduleTemplate}Repository = ${moduleTemplate}Repository;
        $this->initialize();
    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => '{tableName}',
            'foreignkey' => '{foreignKey}',
            'language_id' =>  $this->language,
        ]);
    }

    public function index(Request $request) {
        $canonicalPermission = '{moduleCanonical}';
        $dropdown = $this->nestedset->Dropdown();
        ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request, $this->language);
        $model = '{ModuleTemplate}';
        return view("{moduleView}/index", [
            'dropdown' => $dropdown,
            '{moduleView}s' => ${moduleTemplate}s,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = '{moduleCanonical}/create';
        $dropdown = $this->nestedset->Dropdown();
        return view("{moduleView}/create", [
            'dropdown' => $dropdown,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(Store{ModuleTemplate}Request $request)
    {
        if($this->{moduleTemplate}Service->create($request, $this->language)) {
            return redirect('/{moduleCanonical}')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/{moduleCanonical}')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = '{moduleCanonical}/edit';
        $dropdown = $this->nestedset->Dropdown();
        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
        $album = json_decode(${moduleTemplate}->album);
        return view("{moduleView}/edit", [
            'dropdown' => $dropdown,
            '{moduleView}' => ${moduleTemplate},
            'album' => $album,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(Update{ModuleTemplate}Request $request, string $id)
    {
        if($this->{moduleTemplate}Service->update($id, $request, $this->language)) {
            return redirect('/{moduleCanonical}')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/{moduleCanonical}')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = '{moduleCanonical}/delete';
        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
        return view("{moduleView}/delete", [
            '{moduleView}'=> ${moduleTemplate},
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->{moduleTemplate}Service->destroy($id)) {
            return redirect('/{moduleCanonical}')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/{moduleCanonical}')->with('error', 'Đã xảy ra lỗi.');
    }
}

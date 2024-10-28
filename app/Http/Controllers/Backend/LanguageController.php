<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\TranslateRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;


class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    public function __construct(LanguageService $languageService, LanguageRepository $languageRepository){
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
    }

    public function index(Request $request) {
        $canonicalPermission = 'language';
        $languages = $this->languageService->paginate($request);
        $model = 'language';
        return view("language/index", [
            'languages' => $languages,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'language/create';
        return view("language/create", [
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StoreLanguageRequest $request)
    {
        if($this->languageService->create($request)) {
            return redirect('/language')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/language')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'language/edit';
        $language = $this->languageRepository->findById($id);
        return view("language/edit", [
            'language' => $language,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateLanguageRequest $request, string $id)
    {
        if($this->languageService->update($id, $request)) {
            return redirect('/language')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/language')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'language/delete';
        $language = $this->languageRepository->findById($id);
        return view("language/delete", [
            'language' => $language,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->languageService->destroy($id)) {
            return redirect('/language')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/language')->with('error', 'Đã xảy ra lỗi.');
    }

    public function changeLanguage($id) {
        $language = $this->languageRepository->findById($id);
        if($this->languageService->changeLanguage($id)){
            session(['app_locale' => $language->canonical]);
            App::setLocale($language->canonical);
        }
        return redirect()->back();
    }

    public function translate($id = 0, $language_id = '', $model_name = '') {
        $canonicalPermission = 'language/translate';
        $repositoryInstance = $this->repositoryInstance($model_name);
        $languageRepository = $this->repositoryInstance('Language');
        $methodName = 'get'.$model_name.'ById';
        $currentLanguage = $languageRepository->findByCondition([
            ['canonical', '=', session('app_locale')]
        ]);
        $model = $repositoryInstance->{$methodName}($id, $currentLanguage->id);
        $translatedModel = $repositoryInstance->{$methodName}($id, $language_id);
        $option = [
            'id' => $id,
            'language_id' => $language_id,
            'model_name' => $model_name,
        ];
        return view("language/translate", [
            'model' => $model,
            'translated_model' => $translatedModel,
            'option' => $option,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function storeTranslation(TranslateRequest $request) {
        if($this->languageService->createTranslation($request, $request->input('option'))) {
            return redirect()->back()->with('success', 'Cập nhật thành công.');
        }
        return redirect()->back()->with('error', 'Đã xảy ra lỗi.');
    }

    private function repositoryInstance($model_name){
        $repositoryInterfaceNamespace = '\App\Repositories\\'.ucfirst($model_name).'Repository';
        if(class_exists($repositoryInterfaceNamespace)) {
            $repositoryInstance = app($repositoryInterfaceNamespace);
        }
        return $repositoryInstance;
    }
}

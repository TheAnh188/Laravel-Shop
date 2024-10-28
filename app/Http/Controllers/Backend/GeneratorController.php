<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\GeneratorServiceInterface as GeneratorService;
use App\Repositories\Interfaces\GeneratorRepositoryInterface as GeneratorRepository;
use App\Http\Requests\StoreGeneratorRequest;
use App\Http\Requests\UpdateGeneratorRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;


class GeneratorController extends Controller
{
    protected $generatorService;
    protected $generatorRepository;

    public function __construct(GeneratorService $generatorService, GeneratorRepository $generatorRepository){
        $this->generatorService = $generatorService;
        $this->generatorRepository = $generatorRepository;
    }

    public function index(Request $request) {
        $canonicalPermission = 'generator';
        $generators = $this->generatorService->paginate($request);
        $model = 'generator';
        return view("generator/index", [
            'generators' => $generators,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'generator/create';
        return view("generator/create", [
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StoreGeneratorRequest $request)
    {
        if($this->generatorService->create($request)) {
            return redirect('/generator')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/generator')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'generator/edit';
        $generator = $this->generatorRepository->findById($id);
        return view("generator/edit", [
            'generator' => $generator,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateGeneratorRequest $request, string $id)
    {
        if($this->generatorService->update($id, $request)) {
            return redirect('/generator')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/generator')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'generator/delete';
        $generator = $this->generatorRepository->findById($id);
        return view("generator/delete", [
            'generator' => $generator,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->generatorService->destroy($id)) {
            return redirect('/generator')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/generator')->with('error', 'Đã xảy ra lỗi.');
    }

    private function repositoryInstance($model_name){
        $repositoryInterfaceNamespace = '\App\Repositories\\'.ucfirst($model_name).'Repository';
        if(class_exists($repositoryInterfaceNamespace)) {
            $repositoryInstance = app($repositoryInterfaceNamespace);
        }
        return $repositoryInstance;
    }
}

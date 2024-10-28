<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserCatalogueRequest;
use App\Http\Controllers\Controller;


class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $permissionRepository;

    public function __construct(UserCatalogueService $userCatalogueService, UserCatalogueRepository $userCatalogueRepository, PermissionRepository $permissionRepository){
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request) {
        $canonicalPermission = 'user-catalogue';
        $userCatalogues = $this->userCatalogueService->paginate($request);
        $model = 'userCatalogue';
        return view("user_catalogue/index", [
            'user_catalogues' => $userCatalogues,
            'model' =>$model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'user-catalogue/create';
        return view("user_catalogue/create", [
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StoreUserCatalogueRequest $request)
    {
        if($this->userCatalogueService->create($request)) {
            return redirect('/user-catalogue')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/user-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'user-catalogue/edit';
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        return view("user_catalogue/edit", [
            'user_catalogue'=> $userCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateUserCatalogueRequest $request, string $id)
    {
        if($this->userCatalogueService->update($id, $request)) {
            return redirect('/user-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/user-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'user-catalogue/delete';
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        return view("user_catalogue/delete", [
            'user_catalogue'=> $userCatalogue,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->userCatalogueService->destroy($id)) {
            return redirect('/user-catalogue')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/user-catalogue')->with('error', 'Đã xảy ra lỗi.');
    }

    public function permission()
    {
        $canonicalPermission = 'user-catalogue';
        $userCatalogues = $this->userCatalogueRepository->all(['permissions']);
        $permissions = $this->permissionRepository->all();
        return view("user_catalogue/permission", [
            'user_catalogues' => $userCatalogues,
            'permissions' =>$permissions,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function grantPermission(Request $request)
    {
        if($this->userCatalogueService->setPermission($request)) {
            return redirect('/user-catalogue/permission')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/user-catalogue/permission')->with('error', 'Đã xảy ra lỗi.');
    }
}

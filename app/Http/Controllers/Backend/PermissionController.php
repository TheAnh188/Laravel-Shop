<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;


class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(PermissionService $permissionService, PermissionRepository $permissionRepository){
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request) {
        $canonicalPermission = 'permission';
        $permissions = $this->permissionService->paginate($request);
        $model = 'permission';
        return view("permission/index", [
            'permissions' => $permissions,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'permission/create';
        return view("permission/create", [
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StorePermissionRequest $request)
    {
        if($this->permissionService->create($request)) {
            return redirect('/permission')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/permission')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'permission/edit';
        $permission = $this->permissionRepository->findById($id);
        return view("permission/edit", [
            'permission' => $permission,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdatePermissionRequest $request, string $id)
    {
        if($this->permissionService->update($id, $request)) {
            return redirect('/permission')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/permission')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'permission/delete';
        $permission = $this->permissionRepository->findById($id);
        return view("permission/delete", [
            'permission' => $permission,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->permissionService->destroy($id)) {
            return redirect('/permission')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/permission')->with('error', 'Đã xảy ra lỗi.');
    }
}

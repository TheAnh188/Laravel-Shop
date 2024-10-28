<?php

namespace App\Services;

use App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class PermissionService
 * @package App\Services
 */
class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['permissions.deleted_at', '=', NULL],
        ];
        $perpage = $request->integer('perpage');

        $permissions = $this->permissionRepository->paginate(['id', 'name', 'canonical'], $condition, $perpage, [], ['path' => '/permission'], [], [], []);
        return $permissions;
    }

    public function create(StorePermissionRequest $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token']);
            $payload['user_id'] = Auth::id();
            $this->permissionRepository->create($payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdatePermissionRequest $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', '_method']);
            $this->permissionRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->permissionRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

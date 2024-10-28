<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository ;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserCatalogueRequest;
use App\Models\UserCatalogue;

/**
 * Class UserCatalogueService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    protected $userRepository;

    public function __construct(UserCatalogueRepository $userCatalogueRepository, UserRepository $userRepository){
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->userRepository = $userRepository;
    }

    public function paginate($request) {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['user_catalogues.deleted_at', '=', NULL],
        ];
        $perpage = $request->integer('perpage');

        $userCatalogues = $this->userCatalogueRepository->paginate(['id', 'name', 'description', 'status'], $condition, $perpage, [], ['path' => '/user-catalogue'], [], ['users'], []);
        return $userCatalogues;
    }

    public function create(StoreUserCatalogueRequest $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token']);
            $this->userCatalogueRepository->create($payload);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdateUserCatalogueRequest $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', '_method']);
            $this->userCatalogueRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy(int $id) {
        DB::beginTransaction();
        try {
            $this->userCatalogueRepository->delete($id);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatus($post) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = ($post['value'] == 1) ? 2 : 1;
            $this->userCatalogueRepository->update($post['modelId'], $payload);
            $this->setUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setStatusAll($post) {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $this->userCatalogueRepository->updateByWhereIN('id', $post['id'], $payload);
            $this->setUserStatus($post, $post['value']);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    private function setUserStatus($post, $value) {
        DB::beginTransaction();
        try {
            $array = [];
            if(isset($post['modelId'])) {
                $array[] = $post['modelId'];
            } else {
                $array = $post['id'];
            }
            $payload[$post['field']] = $value;
            $this->userRepository->updateByWhereIN('user_catalogue_id', $array, $payload);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function setPermission($request)
    {
        //NOTE
        //khi cap nhat mot nhom thanh vien tu co quyen thanh k co quyen nao thi k cap nhat dc
        DB::beginTransaction();
        try {
            $permissions = $request->input('permissions');
            if(isset($permissions)) {
                foreach($permissions as $key =>$value) {
                    $userCatalogue = $this->userCatalogueRepository->findById($key);
                    $userCatalogue->permissions()->detach();
                    $userCatalogue->permissions()->sync($value);
                }
            }else {
                $userCatalogues = $this->userCatalogueRepository->all();
                foreach($userCatalogues as $userCatalogue) {
                    $userCatalogue->permissions()->detach();
                }
            }
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository ;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

/**
 * Class UserService
 * @package App\Services
 */
class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function paginate($request) {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        $condition['where'] = [
            ['users.deleted_at', '=', NULL],
        ];
        $perpage = $request->integer('perpage');

        $users = $this->userRepository->paginate(['id', 'name', 'email', 'image', 'phone', 'address', 'status', 'user_catalogue_id'], $condition, $perpage, [], ['path' => '/user'], [], [], []);
        return $users;
    }

    public function create(StoreUserRequest $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'repeat_password']);
            $payload['birthday'] = $this->convertBirthday($payload['birthday']);
            $payload['password'] = Hash::make($payload['password']);
            $payload['image'] = $this->encodeImageToBase64($request);
            $this->userRepository->create($payload);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdateUserRequest $request) {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', '_method']);
            $payload['birthday'] = $this->convertBirthday($payload['birthday']);
            $payload['image'] = $this->encodeImageToBase64($request);
            $this->userRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    private function encodeImageToBase64($request) {
        $base64String = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageData = file_get_contents($image);
            $base64Image = base64_encode($imageData);

            // Xác định định dạng ảnh
            $imageMimeType = $image->getMimeType(); // Ví dụ: image/jpeg, image/png
            $base64String = "data:$imageMimeType;base64,$base64Image";
        }
        return $base64String;
    }

    private function convertBirthday($birthday = ''){
        $cacbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $cacbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }

    public function destroy(int $id) {
        DB::beginTransaction();
        try {
            $this->userRepository->delete($id);
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
            $this->userRepository->update($post['modelId'], $payload);
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
            $this->userRepository->updateByWhereIN('id', $post['id'], $payload);
            DB::commit();
            return true;
        } catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}

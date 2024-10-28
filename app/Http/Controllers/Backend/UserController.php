<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    protected $userService;
    protected $provinceRepository;
    protected $userRepository;

    public function __construct(UserService $userService, ProvinceRepository $provinceRepository, UserRepository $userRepository){
        $this->userService = $userService;
        $this->provinceRepository = $provinceRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request) {
        $canonicalPermission = 'user';
        $users = $this->userService->paginate($request);
        $model = 'user';
        return view("user/index", [
            'users' => $users,
            'model' => $model,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function create() {
        $canonicalPermission = 'user/create';
        $provinces = $this->provinceRepository->all();
        return view("user/create",[
            'provinces' => $provinces,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        if($this->userService->create($request)) {
            return redirect('/user')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/user')->with('error', 'Đã xảy ra lỗi.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $canonicalPermission = 'user/edit';
        $user = $this->userRepository->findById($id);
        $provinces = $this->provinceRepository->all();

        return view("user/edit", [
            'user' => $user,
            'provinces' => $provinces,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        if($this->userService->update($id, $request)) {
            return redirect('/user')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/user')->with('error', 'Đã xảy ra lỗi.');
    }

    public function delete($id) {
        $canonicalPermission = 'user/delete';
        $user = $this->userRepository->findById($id);
        return view("user/delete", [
            'user' => $user,
            'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function destroy(string $id)
    {
        if($this->userService->destroy($id)) {
            return redirect('/user')->with('success', 'Cập nhật thành công.');
        }
        return redirect('/user')->with('error', 'Đã xảy ra lỗi.');
    }
}

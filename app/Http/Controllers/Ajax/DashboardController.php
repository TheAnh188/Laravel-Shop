<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface as UserService;

class DashboardController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function setStatus(Request $request) {
        $post = $request->input();

        $serviceInterfaceNamespace = '\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }

        $flag = $serviceInstance->setStatus($post);

        return response()->json(['flag' => $flag]);
    }

    public function setStatusAll(Request $request) {
        $post = $request->input();

        $serviceInterfaceNamespace = '\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }

        $flag = $serviceInstance->setStatusAll($post);

        return response()->json(['flag' => $flag]);
    }

}

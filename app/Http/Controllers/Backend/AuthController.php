<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function index() {
        // dd(Auth::id());die();
        return view("auth/index");
    }

    public function login(AuthRequest $request) {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            // toastr()->success('Đăng nhập thành công.');
            return redirect('/dashboard/index')->with('success','Đăng nhập thành công.');
        }
        // toastr()->error('Email hoặc mật khẩu không chính xác.');
        return redirect('/auth/login')->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}

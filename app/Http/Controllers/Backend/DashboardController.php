<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        // dd(Auth::id());die();
        return view("dashboard/index");
    }

    public function blank() {
        return view("dashboard/blank");
    }

    public function calendar() {
        return view("dashboard/calendar");
    }

    public function forms() {
        return view("dashboard/forms");
    }

    public function tables() {
        return view("dashboard/tables");
    }

    public function tabs() {
        return view("dashboard/tabs");
    }
}

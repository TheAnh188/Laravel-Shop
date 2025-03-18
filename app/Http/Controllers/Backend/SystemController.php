<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Classes\System;
use Illuminate\Http\Request;
use App\Services\Interfaces\SystemServiceInterface as SystemService;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use App\Models\Language;


class SystemController extends Controller
{

    protected $systemLibrary;
    protected $systemService;
    protected $systemRepository;
    protected $language;

    public function __construct(System $systemLibrary, SystemService $systemService, SystemRepository $systemRepository) {
        $this->middleware(function($request, $next){
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        $this->systemLibrary = $systemLibrary;
        $this->systemService = $systemService;
        $this->systemRepository = $systemRepository;
    }

    public function index()
    {
        $system = $this->systemLibrary->config();
        $systemm = convert_array($this->systemRepository->all(), 'keyword', 'content');
        // dd($systemm);
        return view("system/index", [
            'system' => $system,
            'systemm' => $systemm,
            // 'dropdown' => $dropdown,
            // 'products' => $products,
            // 'model' => $model,
            // 'authorization' => $this->customAuthorize($canonicalPermission),
        ]);
    }

    public function store(Request $request)
    {
        if($this->systemService->save($request, $this->language)) {
            return redirect('/system')->with('success', 'Thêm mới thành công.');
        }
        return redirect('/system')->with('error', 'Đã xảy ra lỗi.');
    }

}

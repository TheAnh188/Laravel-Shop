<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $language;

    public function __construct(){
        // $this->language = session('app_locale');
    }

    // public function currentLanguage() {
    //     return 1;
    // }

    public function customAuthorize($canonicalPermission){
        $authorization = [
            'canonical' => $canonicalPermission,
        ];
        if (Gate::denies('accessibility', $canonicalPermission)) {
            $authorization = [
                'canonical' => $canonicalPermission,
                'message' => __('messages.unauthorized_action'),
            ];
        }
        return $authorization;
    }
}

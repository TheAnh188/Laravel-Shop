<?php

namespace App\Services;

use App\Services\Interfaces\BaseServiceInterface;
use Exception;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{

    public function __construct() {}

    public function nestedSet($nestedSet)
    {
        $nestedSet->Get('level ASC, order ASC');
        $nestedSet->Recursive(0, $nestedSet->Set());
        $nestedSet->Action();
    }

    public function formatRoutePayload($canonical, $module_id, $controller, $language_id) {
        $routePayload = [
            'canonical' => $canonical,
            'module_id' => $module_id,
            'controller' => 'App\Http\Controllers\Frontend\\' . $controller,
            'language_id' => $language_id,
        ];
        return $routePayload;
    }
}

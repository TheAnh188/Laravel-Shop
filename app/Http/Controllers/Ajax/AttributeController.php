<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;

class AttributeController extends Controller
{
    protected $attributeRepository;
    protected $language;

    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->middleware(function ($request, $next) {
            //lazy loading nestedset vi khong the su dung $this->language co gia tri la getLocale o __construct
            //vi __construct load truoc middleware
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }


    public function getAttribute(Request $request)
    {

        $payload = $request->input();
        $attributes = $this->attributeRepository->searchAttributes($payload['search'], $payload['option'], $this->language);
        $attributeArray = $attributes->map(function ($attribute) {
            return [
                'id' => $attribute->id,
                'text' => $attribute->attribute_language->first()->name, //************
            ];
        });
        return response()->json(array('data' => $attributeArray));
    }

    public function loadAttribute(Request $request)
    {
        $payload['attribute'] = $request->input('attribute');
        $formatedAttributes = [];
        foreach ($payload['attribute'] as $key => $value) {
            // Loại bỏ dấu nháy và ép kiểu key thành số nguyên
            $convertedKey = (int) trim($key, "'");
            $formatedAttributes[$convertedKey] = $value;
        }
        $payload['attributeCatalogueId'] = $request->input('attributeCatalogueId');
        $attributeArray = $formatedAttributes[$payload['attributeCatalogueId']];
        //attributeArray = array:2 [
        //     0 => "18"
        //     1 => "17"
        // ]
        $attributes = [];
        if (count($attributeArray)) {
            $attributes = $this->attributeRepository->findAttributeByIdArray($attributeArray, $this->language);
        }
        $temp = [];
        //temp = array:2 [
        //   0 => array:2 [
        //     "id" => 17
        //     "text" => "Màu vàng"
        //   ]
        //   1 => array:2 [
        //     "id" => 18
        //     "text" => "Màu đỏ"
        //   ]
        // ]
        if (count($attributeArray)) {
            foreach ($attributes as $key => $value) {
                $temp[] = [
                    'id' => $value->id,
                    'text' => $value->name,
                ];
            }
        }

        return response()->json(array('data' => $temp));
    }
}

<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\DistrictRepositoryInterface as DistrictRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Models\Ward;


class LocationController extends Controller
{
    protected $districtRepository;
    protected $provinceRepository;

    public function __construct(DistrictRepository $districtRepository, ProvinceRepository $provinceRepository) {
        $this->districtRepository = $districtRepository;
        $this->provinceRepository = $provinceRepository;
    }


    public function getLocation(Request $request){

        $input = $request->input();

        $html = '';

        if($input['target'] == 'districts') {
            $province = $this->provinceRepository->findById($input['data']['location_id'], ['code', 'name'], ['districts']);
            $html = $this->renderHtml($province->districts);
        } else if($input['target'] == 'wards') {
            $district = $this->districtRepository->findById($input['data']['location_id'], ['code', 'name'], ['wards']);
            $html = $this->renderHtml($district->wards, __('messages.user.store.ward'));
        }

        $response = [
            'html' => $html,
        ];
        return response()->json($response);
    }

    private function renderHtml($districts, $root = null) {
        $root = $root ?? __('messages.user.store.district');
        $html = '<option value="0">'.$root.'</option>';
        foreach($districts as $district){
            $html .= '<option value="'.$district->code.'">'.$district->name.'</option>';
        }
        return $html;
    }

}

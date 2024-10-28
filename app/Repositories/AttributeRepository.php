<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Repositories\Interfaces\AttributeRepositoryInterface;

/**
 * Class AttributeRepository
 * @package App\Repositories
 */
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{

    protected $model;

    public function __construct(Attribute $model) {
        $this->model = $model;
    }

    public function getAttributeById(int $id = 0, $language_id = 0) {
        return $this->model->select([
                                        'attributes.id',
                                        'attributes.attribute_catalogue_id',
                                        'attributes.image',
                                        'attributes.icon',
                                        'attributes.album',
                                        'attributes.status',
                                        'attributes.follow',
                                        'tb2.name',
                                        'tb2.description',
                                        'tb2.content',
                                        'tb2.meta_title',
                                        'tb2.meta_keyword',
                                        'tb2.meta_description',
                                        'tb2.canonical',
                                    ])
                            ->join('attribute_language as tb2', 'tb2.attribute_id', '=', 'attributes.id')
                            ->where('tb2.language_id', '=', $language_id)
                            ->with('attribute_catalogues')
                            ->find($id);
    }

    public function searchAttributes(string $keyword = '', array $option = [], int $language_id){
        return $this->model->whereHas('attribute_catalogues', function($query) use ($option){
            $query->where('attribute_catalogue_id', $option['attributeCatalogueId']);//tu dong ket bang attribute_catalogue_attribute vì da khai bao relation trong model
        })->whereHas('attribute_language', function($query) use ($keyword){
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        })->get();
    }

    public function findAttributeByIdArray(array $attributeIdArray = [], int $language_id = 0){
        return $this->model->select([
            'attributes.id',
            'tb2.name',
        ])->join('attribute_language as tb2', 'tb2.attribute_id', '=', 'attributes.id')
        ->where('tb2.language_id', '=', $language_id)
        ->whereIn('attributes.id', $attributeIdArray)
        ->get();
    }


}

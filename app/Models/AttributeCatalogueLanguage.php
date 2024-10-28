<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;


class AttributeCatalogueLanguage extends Model
{
    use HasFactory, SoftDeletes, QueryScope;

    protected $table = 'attribute_catalogue_language';

    public function attribute_catalogue(){
        return $this->belongsTo(AttributeCatalogue::class, 'attribute_catalogue_id', 'id');
    }
}

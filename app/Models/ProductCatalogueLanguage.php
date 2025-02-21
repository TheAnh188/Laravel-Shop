<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;


class ProductCatalogueLanguage extends Model
{
    use HasFactory, SoftDeletes, QueryScope;

    protected $table = 'product_catalogue_language';

    public function product_catalogue(){
        return $this->belongsTo(ProductCatalogue::class, 'product_catalogue_id', 'id');
    }
}

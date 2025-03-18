<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class Product extends Model
{
    use HasFactory, SoftDeletes, QueryScope;

    protected $fillable = [
        'product_catalogue_id',
        'image',
        'icon',
        'album',
        'status',
        'order',
        'user_id',
        'follow',
        'price',
        'made_in',
        'code',
        'attribute_catalogue',
        'attributes',
        'variants',
    ];

    protected $table = 'products';

    public function languages() {
        return $this->belongsToMany(Language::class, 'product_language', 'product_id', 'language_id')
                ->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')
                ->withTimestamps();
    }

    public function product_catalogues() {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_product', 'product_id', 'product_catalogue_id');
    }

    public function product_variants() {
        return $this->hasMany( ProductVariant::class, 'product_id', 'id');
    }

    // public function post_catalogue_language() {
    //     return $this->hasMany(PostCatalogueLanguage::class, 'post_catalogue_id', 'id');
    // }
}

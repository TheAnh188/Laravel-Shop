<?php

namespace App\Models;

use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class ProductCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScope;
    //Dau vao PostCatalogue
    //ModuleTemplate = PostCatalogue
    //tableName = post_catalogue
    //relatedModel = post
    //RelatedModel = Post
    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'status',
        'order',
        'follow',
        'user_id',
    ];

    protected $table = 'product_catalogues';

    public function languages() {
        return $this->belongsToMany(Language::class, 'product_catalogue_language', 'product_catalogue_id', 'language_id')
                ->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')
                ->withTimestamps();
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_catalogue_product', 'product_catalogue_id', 'product_id');
    }

    public function product_catalogue_language() {
        return $this->hasMany(ProductCatalogueLanguage::class, 'product_catalogue_id', 'id');
    }

    public static function isChildNode($id = 0) {
        $productCatalogue = ProductCatalogue::find($id);
        if(!$productCatalogue) {
            return false;
        }
        if($productCatalogue->rgt - $productCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}

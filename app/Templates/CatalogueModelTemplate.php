<?php

namespace App\Models;

use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class {ModuleTemplate} extends Model
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

    protected $table = '{tableName}s';

    public function languages() {
        return $this->belongsToMany(Language::class, '{tableName}_language', '{tableName}_id', 'language_id')
                ->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')
                ->withTimestamps();
    }

    public function {relatedModel}s() {
        return $this->belongsToMany({RelatedModel}::class, '{tableName}_{relatedModel}', '{tableName}_id', '{relatedModel}_id');
    }

    public function {tableName}_language() {
        return $this->hasMany({ModuleTemplate}Language::class, '{tableName}_id', 'id');
    }

    public static function isChildNode($id = 0) {
        ${relatedModel}Catalogue = {ModuleTemplate}::find($id);
        if(!${relatedModel}Catalogue) {
            return false;
        }
        if(${relatedModel}Catalogue->rgt - ${relatedModel}Catalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}

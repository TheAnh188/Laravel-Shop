<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class Post extends Model
{
    use HasFactory, SoftDeletes, QueryScope;

    protected $fillable = [
        'post_catalogue_id',
        'image',
        'icon',
        'album',
        'status',
        'order',
        'user_id',
        'follow',
    ];

    protected $table = 'posts';

    public function languages() {
        return $this->belongsToMany(Language::class, 'post_language', 'post_id', 'language_id')
                ->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')
                ->withTimestamps();
    }

    public function post_catalogues() {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }

    // public function post_catalogue_language() {
    //     return $this->hasMany(PostCatalogueLanguage::class, 'post_catalogue_id', 'id');
    // }
}

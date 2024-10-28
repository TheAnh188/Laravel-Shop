<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;


class PostCatalogueLanguage extends Model
{
    use HasFactory, SoftDeletes, QueryScope;

    protected $table = 'post_catalogue_language';

    public function post_catalogue(){
        return $this->belongsTo(PostCatalogue::class, 'post_catalogue_id', 'id');
    }
}

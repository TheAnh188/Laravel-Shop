<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class AttributeLanguage extends Model
{
    use HasFactory, SoftDeletes, QueryScope;

    protected $table = 'attribute_language';

    public function attribute(){
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}

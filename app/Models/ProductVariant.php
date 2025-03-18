<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScope;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, QueryScope;

    protected $fillable = [
        'product_id',
        'code',
        'quantity',
        'sku',
        'price',
        'barcode',
        'filename',
        'file_url',
        'album',
        'status',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'product_variant_language', 'product_variant_id', 'language_id')
            ->withPivot('name')
            ->withTimestamps();
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_variant_attribute', 'product_variant_id', 'attribute_id');
    }
}

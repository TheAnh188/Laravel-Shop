<?php

namespace App\Models;

use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generator extends Model
{
    use HasFactory, QueryScope;

    protected $fillable = [
        'id',
        'name',
        'schema',
    ];

    protected $table = 'generators';
}

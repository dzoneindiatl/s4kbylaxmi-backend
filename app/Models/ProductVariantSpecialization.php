<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantSpecialization extends Model
{
    use HasFactory;

    protected $table = 'product_variant_specifications'; 

    protected $fillable = [
        'id',
        'product_id',
        'variant_id',
        'variant_value_id',
        'content_1',
        'content_2',
        'content_3',
        'content_4',
        'content_5',
        'content_6',
        'content_7',
        'content_8',
        'created_at',
        'updated_at'
    ];
}

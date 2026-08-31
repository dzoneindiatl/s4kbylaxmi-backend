<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetailManager extends Model {
    use HasFactory;
    protected $table = 'product_detail_manager';
    protected $fillable = [
        'section_name',
        'field_type',
        'created_at'
    ];
}

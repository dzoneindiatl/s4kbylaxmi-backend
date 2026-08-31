<?php 
namespace App\Models;
use Illuminate\Support\Facades\DB;
use App\Models\ProductGraphics;
use Illuminate\Database\Eloquent\Model;
/**
 * Review Model
 */
class ProductVariantCombination extends Model {

	
/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'product_variant_combinations';

    protected $fillable = [
        'product_id',
        'combination_id',
        'primary_variant_value_id',
        'sku',
        'selling_price',
        'discount',
        'discount_type',
        'price',
        'qty',
        "status",
        "specialization",
        "is_out_of_stock"
    ];
    
}// end EmailAction class

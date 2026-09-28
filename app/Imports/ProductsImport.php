<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantValue;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\ProductVariantCombination;
use App\Models\CategoryAttribute;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProductImageProcessJob;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        if (empty($row['product_name']) || empty($row['sku'])) {
            return null;
        }
        
        return DB::transaction(function () use ($row) {
            $category_id = Category::where('name', $row['category'])->value('id');
            $sub_category_id = Category::where('name', $row['sub_category'])->value('id');
            $child_category_id = Category::where('name', $row['child_category'])->value('id');

            $product_type = strtolower($row['product_type'] ?? 'simple') === 'simple' ? 1 : 2;
            $is_new_arrivals = strtoupper($row['is_new_arrivals'] ?? 'N') === 'Y' ? 1 : 0;
            $best_seller = strtoupper($row['best_seller'] ?? 'N') === 'Y' ? 1 : 0;
            
            // $attributes = array_map('trim', explode(',', $row['attributes']));
            // $attribute_values = array_map('trim', explode(',', $row['attribute_value']));
        $product = Product::updateOrCreate(
            ['sku' => $row['sku']],
            [
                'product_type' => $product_type,
                'name' => $row['product_name'],
                'main_category_id' => $category_id,
                'main_sub_category_id' => $sub_category_id,
                'main_child_category_id' => $child_category_id,
                'buying_price' => $row['buying_price'] ?? 0,
                'selling_price' => $row['selling_price'] ?? 0,
                'discount' => $row['discount'] ?? 0,
                'discount_type' => $row['discount_type'] ?? 'flat',
                'qty' => $row['qty'] ?? 0,
                'description' => $row['description'],
                'specification' => $row['specification'],
                'product_details' => $row['product_details'],
                'short_description' => $row['short_description'],
                'meta_title' => $row['meta_title'],
                'meta_keywords' => $row['meta_keywords'],
                'meta_description' => $row['meta_description'],
                'weight' => $row['weight'],
                'weight_type' => $row['weight_type'],
                'hsn' => $row['hsn'],
                'wash_care' => $row['wash_care'],
                'others' => $row['others'],
                'in_stock' => 1,
                'is_new_arrivals' => $is_new_arrivals,
                'best_seller' => $best_seller,
                'max_selling_units' => $row['max_selling_units'],
                'min_selling_units' => $row['min_selling_units'],
                'is_active' => '1',
            ]
        );
       
            // Attribute
            if ($row['attributes_1']) {
                $attributeData = Attribute::firstOrCreate(['name' => $row['attributes_1']]);
                $attribute_value_detail = AttributeValue::firstOrCreate([
                    'name' => $row['attribute_value_1'],
                    'attribute_id' => $attributeData->id
                ]);

                CategoryAttribute::firstOrCreate([
                    'category_id' => $category_id,
                    'attribute_id' => $attributeData->id
                ]);

                if($attribute_value_detail){
                    ProductAttribute::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_id' => $attribute_value_detail->attribute_id,
                        'attribute_value_id' => $attribute_value_detail->id,
                    ]);
                }
            }

            if ($row['attributes_2']) {
                $attributeData = Attribute::firstOrCreate(['name' => $row['attributes_2']]);
                $attribute_value_detail = AttributeValue::firstOrCreate([
                    'name' => $row['attribute_value_2'],
                    'attribute_id' => $attributeData->id
                ]);
                CategoryAttribute::firstOrCreate([
                    'category_id' => $category_id,
                    'attribute_id' => $attributeData->id
                ]);
                $attribute_value_detail = AttributeValue::where('name', $row['attribute_value_2'])->first();
                if($attribute_value_detail){
                    ProductAttribute::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_id' => $attribute_value_detail->attribute_id,
                        'attribute_value_id' => $attribute_value_detail->id,
                    ]);
                }
                
            }

            if ($row['attributes_3']) {
                $attributeData = Attribute::firstOrCreate(['name' => $row['attributes_3']]);
                $attribute_value_detail = AttributeValue::firstOrCreate([
                    'name' => $row['attribute_value_3'],
                    'attribute_id' => $attributeData->id
                ]);
                CategoryAttribute::firstOrCreate([
                    'category_id' => $category_id,
                    'attribute_id' => $attributeData->id
                ]);
                $attribute_value_detail = AttributeValue::where('name', $row['attribute_value_3'])->first();
                if($attribute_value_detail){
                    ProductAttribute::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_id' => $attribute_value_detail->attribute_id,
                        'attribute_value_id' => $attribute_value_detail->id,
                    ]);
                }
                
            }
            if ($row['attributes_4']) {
                $attributeData = Attribute::firstOrCreate(['name' => $row['attributes_4']]);
                $attribute_value_detail = AttributeValue::firstOrCreate([
                    'name' => $row['attribute_value_4'],
                    'attribute_id' => $attributeData->id
                ]);
                CategoryAttribute::firstOrCreate([
                    'category_id' => $category_id,
                    'attribute_id' => $attributeData->id
                ]);
                $attribute_value_detail = AttributeValue::where('name', $row['attribute_value_4'])->first();
                if($attribute_value_detail){
                    ProductAttribute::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_id' => $attribute_value_detail->attribute_id,
                        'attribute_value_id' => $attribute_value_detail->id,
                    ]);
                }
                
            }

            $variant_main = null;
            // $variant_main = VariantValue::where('name', trim($row['v_main']))->value('id');
            $imagePath = config('constant.PRODUCT_IMAGE_ROOT_PATH');

            $images = [
                'front' => array_map('trim', explode(',', $row['front_image'])),
                'back' => array_map('trim', explode(',', $row['back_image'])),
                'variant' => array_map('trim', explode(',', $row['variant_image'])),
                'image1' => array_map('trim', explode(',', $row['image1'])),
                'image2' => array_map('trim', explode(',', $row['image2'])),
                'image3' => array_map('trim', explode(',', $row['image3'])),
                'image4' => array_map('trim', explode(',', $row['image4'])),
                'image5' => array_map('trim', explode(',', $row['image5'])),
            ];
            
            if($product_type == 2){
                // Variants 
                $colors = array_map('trim', explode(',', $row['variant_value']));
                $sizes = $this->parseDelimitedString($row['size']);
                $skus = $this->parseDelimitedString($row['v_sku']);
                $prices = $this->parseDelimitedString($row['v_price']);
                $sale_prices = $this->parseDelimitedString($row['v_sale_price']);
                $discount_types = $this->parseDelimitedString($row['v_discount_type']);
                $discounts = $this->parseDelimitedString($row['v_discount']);
                $qtys = $this->parseDelimitedString($row['v_qty']);

                $colorVariant = ProductVariant::firstOrCreate([
                    'product_id' => $product->id,
                    'variant_id' => 1,
                ]);

                $sizeVariant = ProductVariant::firstOrCreate([
                    'product_id' => $product->id,
                    'variant_id' => 2,
                ]);         
                
                foreach($colors as $i => $c){
                    
                    $is_main = strtolower(trim($row['v_main'])) == strtolower($c);
                    $colorValueId = VariantValue::where('name', trim($c))->value('id');
                    ProductVariantValue::firstOrCreate([
                        'product_id' => $product->id,
                        'product_variant_id' => $colorVariant->id,
                        'variant_value_id' => $colorValueId,
                    ], ['is_main' => $is_main ]);

                    foreach($sizes[$i] as $j => $s){
                        $sizeValueId = VariantValue::where('name', $s)->value('id');
                        ProductVariantValue::firstOrCreate([
                            'product_id' => $product->id,
                            'product_variant_id' => $sizeVariant->id,
                            'variant_value_id' => $sizeValueId,
                        ], ['is_main' => 0]);

                        $pvc_skus = isset($skus[$i][$j]) ? $skus[$i][$j] : $c."_".$s;
                        $pvc_prices = isset($prices[$i][$j]) ? $prices[$i][$j] : $row['buying_price'];
                        $pvc_sale_prices = isset($sale_prices[$i][$j]) ? $sale_prices[$i][$j] : $row['selling_price'];
                        $pvc_discounts = isset($discounts[$i][$j]) ? $discounts[$i][$j] : 0;
                        $pvc_discount_types = isset($discount_types[$i][$j]) ? $discount_types[$i][$j] : null;
                        $pvc_qtys = isset($qtys[$i][$j]) ? $qtys[$i][$j] : $row['qty'];
                        // echo '<pre>';
                        // print_r([
                        //     'combination_id' => json_encode([$colorValueId, $sizeValueId]),
                        //     'price' => trim($pvc_prices),
                        //     'selling_price' => trim($pvc_sale_prices),
                        //     'discount' => trim($pvc_discounts),
                        //     'discount_type' => trim($pvc_discount_types) == 'flat' ? 'flate' : trim($pvc_discount_types),
                        //     'qty' => $pvc_qtys ?? 0,
                        //     'product_id' => $product->id,
                        //     'sku' => trim($pvc_skus),
                        // ]);

                        ProductVariantCombination::firstOrCreate([
                            'product_id' => $product->id,
                            'sku' => trim($pvc_skus),
                        ], [
                            'combination_id' => json_encode([$colorValueId, $sizeValueId]),
                            'price' => trim($pvc_prices),
                            'selling_price' => trim($pvc_sale_prices),
                            'discount' => trim($pvc_discounts),
                            'discount_type' => trim($pvc_discount_types) == 'flat' ? 'flate' : trim($pvc_discount_types),
                            'qty' => $pvc_qtys ?? 0,
                        ]);
                    }

                    
                    $graphics = [
                        'front' => isset($images['front'][$i]) ? $images['front'][$i] : $images['front'][0],
                        'back' => isset($images['back'][$i]) ? $images['back'][$i] : $images['back'][0],
                        'variant' => isset($images['variant'][$i]) ? $images['variant'][$i] : $images['variant'][0],
                        'image1' => isset($images['image1'][$i]) ? $images['image1'][$i] : null,
                        'image2' => isset($images['image2'][$i]) ? $images['image2'][$i] : null,
                        'image3' => isset($images['image3'][$i]) ? $images['image3'][$i] : null,
                        'image4' => isset($images['image4'][$i]) ? $images['image4'][$i] : null,
                        'image5' => isset($images['image5'][$i]) ? $images['image5'][$i] : null,
                    ];

                    $uniqueUrls = [];
                    $coreTypes = ['front', 'back', 'variant'];
                    // echo "<pre>";
                    // foreach ($graphics as $type => $url) {
                    //     if (empty($url) || $type === 'same_image' || !is_string($url)) {
                    //         continue;
                    //     }
                    //     $url = $this->sanitizeDriveUrl($url);
                    //     // Group types by URL
                    //     $uniqueUrls[$url][] = $type;
                    // }
                    
                    // print_r([$c, $uniqueUrls, $graphics]);
                    
                    ProductImageProcessJob::dispatch($product->id, $graphics, $imagePath, $colorValueId);
                }
            }
            else {
                $graphics = [
                    'front' => isset($images['front'][0]) ? $images['front'][0] : $images['front'][0],
                    'back' => isset($images['back'][0]) ? $images['back'][0] : $images['back'][0],
                    'variant' => isset($images['variant'][0]) ? $images['variant'][0] : $images['variant'][0],
                    'image1' => isset($images['image1'][0]) ? $images['image1'][0] : null,
                    'image2' => isset($images['image2'][0]) ? $images['image2'][0] : null,
                    'image3' => isset($images['image3'][0]) ? $images['image3'][0] : null,
                    'image4' => isset($images['image4'][0]) ? $images['image4'][0] : null,
                    'image5' => isset($images['image5'][0]) ? $images['image5'][0] : null,
                ];

                ProductImageProcessJob::dispatch($product->id, $graphics, $imagePath, $variant_main);
            }

            // die;
            //     $sizes = isset($row['size']) ? array_map('trim', explode(',', $row['size'])) : [];
            //     $skus = array_map('trim', explode(',', $row['v_sku']));
            //     $prices = array_map('trim', explode(',', $row['v_price']));
            //     $sale_prices = array_map('trim', explode(',', $row['v_sale_price']));
            //     $discounts = array_map('trim', explode(',', $row['v_discount']));
            //     $discount_types = array_map('trim', explode(',', $row['v_discount_type']));
            //     $qtys = array_map('trim', explode(',', $row['v_qty']));
            //     // Color Variant
            //     $colorValueId = VariantValue::where('name', trim($row['v_main']))->value('id');

            //     $colorVariant = ProductVariant::firstOrCreate([
            //         'product_id' => $product->id,
            //         'variant_id' => 1,
            //     ]);

            //     ProductVariantValue::firstOrCreate([
            //         'product_id' => $product->id,
            //         'product_variant_id' => $colorVariant->id,
            //         'variant_value_id' => $colorValueId,
            //     ], ['is_main' => true]);

            //     // Size Variant
            //     $sizeVariant = ProductVariant::firstOrCreate([
            //         'product_id' => $product->id,
            //         'variant_id' => 2,
            //     ]);

            //     foreach ($sizes as $i => $size) {

            //         $sizeValueId = VariantValue::where('name', $size)->value('id');
            //         ProductVariantValue::firstOrCreate([
            //             'product_id' => $product->id,
            //             'product_variant_id' => $sizeVariant->id,
            //             'variant_value_id' => $sizeValueId,
            //         ], ['is_main' => $i === 0]);

            //         ProductVariantCombination::firstOrCreate([
            //             'product_id' => $product->id,
            //             'sku' => trim($skus[$i]),
            //         ], [
            //             'combination_id' => json_encode([$colorValueId, $sizeValueId]),
            //             'price' => trim($prices[$i]),
            //             'selling_price' => trim($sale_prices[$i]),
            //             'discount' => trim($discounts[$i]),
            //             'discount_type' => trim($discount_types[$i]) == 'flat' ? 'flate' : 'percentage',
            //             'qty' => $qtys[$i] ?? 0,
            //         ]);
            //     }
           
           

            // Save Product Graphics in Background
            
           
            return $product;
        });
    }

    /**
     * Parses a delimited string into a multidimensional array.
     *
     * @param string|null $input      The input string (e.g., "red,black|orange,pink")
     * @param string      $outerDelim The delimiter separating outer groups (default: '|')
     * @param string      $innerDelim The delimiter separating inner values (default: ',')
     * @return array
     */
    private function parseDelimitedString(?string $input, string $outerDelim = '|', string $innerDelim = ','): array
    {
        if (empty($input)) {
            return [];
        }

        // Split by pipe '|' first
        $groups = explode($outerDelim, $input);

        // Map over each group and split by comma ','
        return array_map(function ($group) use ($innerDelim) {
            return array_map('trim', explode($innerDelim, $group));
        }, $groups);
    }

    private function sanitizeDriveUrl(string $url): string
    {
        return strtok($url, '?');
    }



}

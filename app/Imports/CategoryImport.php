<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\CategoryTax;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\SizeChartManager; 
use App\Models\Variant; 
use App\Models\Attribute;
use App\Models\Tax;
use App\Models\CategoryVariant; 
use App\Models\CategoryAttribute;  
use Illuminate\Support\Str;

class CategoryImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
 {

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {       
        if (!isset($row['name']) || empty($row['name'])) {
            return null;
        } 
        if($row['size_chart']){
            $convertName = strtolower(str_replace(' ','_',$row['size_chart'])); 
            $getChartId = SizeChartManager::where('chart_format',$convertName)->where('status','1')->first(); 
        }
        if($row['variants']){
            $getname = explode(',',$row['variants']); 
            $variantIds = Variant::whereIn('name',$getname)->where('is_active',1)->pluck('id'); 
        } 
        if($row['attributes']){
            $attributeId = Attribute::Where('name',$row['attributes'])->where('is_active',1)->value('id'); 
        }
        if (!empty($row['taxs'])) {
            $taxData = explode(',', $row['taxs']);
            $taxOption = strtolower(trim($taxData[0] ?? ''));
            $taxType = strtolower(trim($taxData[1] ?? ''));
            $taxRate = trim($taxData[2] ?? '');

            $taxId = Tax::where('tax_option', $taxOption)
                ->where('tax_type', $taxType)
                ->where('tax_rate',$taxRate)
                ->where('is_active', 1)
                ->value('id');
        }
        $category =  new Category([
            'name'            => $row['name'] ?? null,
            'slug'            => Str::slug($row['name']),
            'category_type'   => $row['category_type'] ?? null,
            'image'           => $row['image'] ?? null,
            'thumbnail_image' => $row['thumbnail_image'] ?? null,
            'video'           => $row['video'] ?? null,
            'show_on_home'    => isset($row['show_on_home']) ? (int) $row['show_on_home'] : 0,
            'show_menu'       => isset($row['show_on_menu']) ? (int) $row['show_on_menu'] : 0, 
            'size_chart_id'   => $getChartId->id,
        ]); 
        $category->save(); 
        if($taxId){
            CategoryTax::create([
                'category_id'=>$category->id, 
                'tax_id'=>$taxId,
                'tax_option'=>$taxOption,
                'tax_type'=>$taxType
            ]); 
        }
        if (!empty($variantIds)) {
            foreach ($variantIds as $variantId) {
                CategoryVariant::create([
                    'category_id' => $category->id,
                    'variant_id' => $variantId,
                ]);
            }
        }
        if ($attributeId) {
            CategoryAttribute::create([
                'category_id' => $category->id,
                'attribute_id' => $attributeId,
            ]);
        }

    }
    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChartSection extends Model
{
    use HasFactory;
    protected $table = "size_chart_sections"; 
    public function measurements()
    {
        return $this->hasMany(SizeChartMeasurement::class, 'size_chart_section_id')->orderBy('sort_order');
    }
}

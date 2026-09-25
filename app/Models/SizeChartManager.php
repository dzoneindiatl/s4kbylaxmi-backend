<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChartManager extends Model
{
    use HasFactory;

    protected $table = "size_chart_managers"; 
    public function sections()
    {
        return $this->hasMany(SizeChartSection::class, 'size_chart_id')->orderBy('sort_order');
    }

    public function sizes()
    {
        return $this->hasMany(SizeChartSizes::class, 'size_chart_id')->orderBy('sort_order');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChartMeasureMentValue extends Model
{
    use HasFactory;
    protected $table = "size_chart_measurement_values"; 
    public function size()
    {
        return $this->belongsTo(SizeChartSizes::class, 'size_id');
    }
}

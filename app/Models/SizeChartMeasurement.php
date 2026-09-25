<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChartMeasurement extends Model
{
    use HasFactory;

    protected $table = "size_chart_measurements"; 
    public function values()
    {
        return $this->hasMany(SizeChartMeasureMentValue::class, 'measurement_id');
    }
}

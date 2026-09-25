<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('size_chart_measurement_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('size_chart_id')->nullable(); 
            $table->bigInteger('section_id')->nullable(); 
            $table->bigInteger('measurement_id')->nullable(); 
            $table->bigInteger('size_id')->nullable(); 
            $table->decimal('value_inch', 10, 2)->nullable();
            $table->decimal('value_cm', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_chart_measure_ment_values');
    }
};

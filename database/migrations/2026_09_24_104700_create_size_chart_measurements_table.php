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
        Schema::create('size_chart_measurements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('size_chart_section_id')->nullable(); 
            $table->string('measurement_name')->nullable(); 
            $table->unsignedInteger('sort_order')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_chart_measurements');
    }
};

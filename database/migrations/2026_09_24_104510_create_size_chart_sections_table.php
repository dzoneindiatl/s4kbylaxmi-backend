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
        Schema::create('size_chart_sections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('size_chart_id'); 
            $table->string('section_type')->nullable(); 
            $table->unsignedInteger('sort_order')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_chart_sections');
    }
};

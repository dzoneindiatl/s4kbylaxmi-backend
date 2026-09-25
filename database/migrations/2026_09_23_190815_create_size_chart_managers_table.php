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
        Schema::create('size_chart_managers', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->enum('type',['inch','cm'])->default('inch'); 
            $table->string('chart_image')->nullable(); 
            $table->text('chart_description')->nullable(); 
            $table->enum('status',['1','0']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_chart_managers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('car_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('favorite_car_id')
                  ->constrained('favorite_cars')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->binary('content');
            $table->string('mime', 64)->nullable();
            $table->timestamps();
        });
        
        DB::statement('ALTER TABLE car_images MODIFY COLUMN content LONGBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_images');
    }
};

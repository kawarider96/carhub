<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('favorite_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreignId('car_model_id')
                  ->constrained('car_models')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->integer('year')->nullable();
            $table->string('color', 40)->nullable();
            $table->string('fuel', 40)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_cars');
    }
};

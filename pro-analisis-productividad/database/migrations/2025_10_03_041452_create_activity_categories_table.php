<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_activity_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#3b82f6'); // Color para UI
            $table->string('icon')->nullable(); // Icono para UI
            $table->text('description')->nullable();
            $table->boolean('is_productive')->default(true);
            $table->integer('productivity_weight')->default(100); // 0-100
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_system')->default(false); // Categorías del sistema
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_categories');
    }
};
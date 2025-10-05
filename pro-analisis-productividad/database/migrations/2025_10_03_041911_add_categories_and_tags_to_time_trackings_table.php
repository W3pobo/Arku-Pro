<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_categories_and_tags_to_time_trackings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('time_trackings', function (Blueprint $table) {
            $table->foreignId('activity_category_id')->nullable()->constrained('activity_categories');
            $table->integer('focus_level')->default(50); // 0-100
            $table->integer('energy_level')->default(50); // 0-100
            $table->text('notes')->nullable();
        });

        // Tabla pivote para etiquetas
        Schema::create('time_tracking_productivity_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_tracking_id')->constrained()->onDelete('cascade');
            $table->foreignId('productivity_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('time_trackings', function (Blueprint $table) {
            $table->dropForeign(['activity_category_id']);
            $table->dropColumn(['activity_category_id', 'focus_level', 'energy_level', 'notes']);
        });
        
        Schema::dropIfExists('time_tracking_productivity_tags');
    }
};
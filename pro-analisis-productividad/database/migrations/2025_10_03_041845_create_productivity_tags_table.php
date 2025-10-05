<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_productivity_tags_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productivity_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // focus_level, distraction, energy_level, etc.
            $table->string('color')->default('#6b7280');
            $table->integer('impact_score')->default(0); // -100 to +100
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productivity_tags');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_abouts', function (Blueprint $table) {
            $table->id();
            // Hero section
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            // Gallery images
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->string('image_4')->nullable();
            // Statistics
            $table->string('statistic_title_1')->nullable();
            $table->text('statistic_description_1')->nullable();
            $table->string('statistic_title_2')->nullable();
            $table->text('statistic_description_2')->nullable();
            $table->string('statistic_title_3')->nullable();
            $table->text('statistic_description_3')->nullable();
            // Mission
            $table->string('mission_title')->nullable();
            $table->text('mission_description')->nullable();
            $table->string('mission_image')->nullable();
            // Vision
            $table->string('vision_title')->nullable();
            $table->text('vision_description')->nullable();
            $table->string('vision_image')->nullable();
            // Team
            $table->string('team_section_title')->nullable();
            $table->text('team_section_description')->nullable();
            $table->json('team_members')->nullable(); // [{name, designation, image, facebook_link, ...}]
            // Core Values
            $table->string('core_value_section_title')->nullable();
            $table->text('core_value_section_description')->nullable();
            $table->json('core_values')->nullable(); // [{icon, title, description}]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_abouts');
    }
};

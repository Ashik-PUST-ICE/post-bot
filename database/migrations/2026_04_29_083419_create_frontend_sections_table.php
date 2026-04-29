<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // hero_area, features, services, core_features, choose_us, pricing, testimonials_area, faqs_area, demo_ection
            $table->string('page_title')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->tinyInteger('status')->default(1); // 1 = active, 0 = inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_sections');
    }
};

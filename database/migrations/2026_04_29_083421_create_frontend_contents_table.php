<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_contents', function (Blueprint $table) {
            $table->id();
            // type: feature | service | core_feature | choose_us | faq | testimonial
            $table->string('type');
            $table->string('name')->nullable();        // service brand name
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();   // service sub-heading
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('others')->nullable();        // service bullet-points
            $table->decimal('rating', 3, 1)->nullable(); // testimonial rating
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_contents');
    }
};

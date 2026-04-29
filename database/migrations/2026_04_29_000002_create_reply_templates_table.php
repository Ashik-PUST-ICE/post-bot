<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reply_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tenant_id')->nullable();
            $table->string('title');                              // e.g. "Business Hours"
            $table->text('content');                              // supports {customer_name}, {business_name}, {platform}
            $table->string('platform')->default('all')
                ->comment('all | facebook | whatsapp | instagram');
            $table->unsignedBigInteger('usage_count')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reply_templates');
    }
};

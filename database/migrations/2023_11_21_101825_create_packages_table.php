<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('slug');
            $table->integer('page_limit')->nullable()->default(0);
            $table->integer('message_limit')->nullable()->default(0);
            $table->text('others')->nullable();
            $table->decimal('monthly_price', 12, 2)->default(0.00);
            $table->decimal('yearly_price', 12, 2)->default(0.00);
            $table->string('stripe_monthly_plan_id')->nullable();
            $table->string('stripe_yearly_plan_id')->nullable();
            $table->string('stripe_product_id')->nullable();
            $table->tinyInteger('status')->default(DEACTIVATE)->comment('active for 1 , deactivate for 0');
            $table->tinyInteger('is_default')->default(DEACTIVATE)->comment('active for 1 , deactivate for 0');
            $table->tinyInteger('is_trail')->default(DEACTIVATE)->comment('active for 1 , deactivate for 0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};

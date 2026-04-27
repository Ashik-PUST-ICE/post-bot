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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('basic_info')->nullable();
            $table->string('basic_first_name')->nullable();
            $table->string('basic_last_name')->nullable();
            $table->string('basic_phone')->nullable();
            $table->string('basic_email')->nullable();
            $table->string('basic_company')->nullable();
            $table->longText('billing_info')->nullable();
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_zip_code')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_country')->nullable();
            $table->longText('shipping_info')->nullable();
            $table->longText('shipping_first_name')->nullable();
            $table->longText('shipping_last_name')->nullable();
            $table->longText('shipping_email')->nullable();
            $table->longText('shipping_phone')->nullable();
            $table->string('shipping_zip_code')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_country')->nullable();
            $table->tinyInteger('shipping_method')->nullable(SHIPPING_METHOD_FREE);
            $table->longText('payment')->nullable();
            $table->decimal('revenue', 12, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
};

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
        Schema::create('subscription_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->string('order_id')->nullable();
            $table->tinyInteger('duration_type')->default(DURATION_MONTH);
            $table->string('payment_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('discount', 12, 2)->default(0);
            $table->tinyInteger('discount_type')->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0)->nullable();
            $table->decimal('transaction_amount', 12, 2)->default(0)->nullable();
            $table->string('system_currency')->nullable();
            $table->unsignedBigInteger('gateway_id');
            $table->string('gateway_currency')->nullable();
            $table->decimal('conversion_rate', 12, 2)->default(1)->nullable();
            $table->tinyInteger('payment_status')->default(PAYMENT_STATUS_PENDING)->comment('0=pending, 1=paid, 2=cancelled');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('bank_deposit_by')->nullable();
            $table->unsignedBigInteger('bank_deposit_slip_id')->nullable();
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
        Schema::dropIfExists('subscription_orders');
    }
};

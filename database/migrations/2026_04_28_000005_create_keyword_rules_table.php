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
        Schema::create('keyword_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');              // admin owner
            $table->string('tenant_id')->nullable();             // tenant isolation
            $table->unsignedBigInteger('platform_connection_id')->nullable(); // null = all platforms
            $table->string('keyword');                           // trigger keyword / phrase
            $table->tinyInteger('match_type')->default(1)->comment('1=contains, 2=exact, 3=starts_with');
            $table->text('reply_template');                      // static reply text (if not using AI)
            $table->tinyInteger('use_ai')->default(DEACTIVATE)->comment('1=use AI with context, 0=static reply');
            $table->tinyInteger('status')->default(STATUS_ACTIVE)->comment('1=active, 0=deactivate');
            $table->unsignedSmallInteger('priority')->default(0)->comment('higher number = higher priority');
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
        Schema::dropIfExists('keyword_rules');
    }
};

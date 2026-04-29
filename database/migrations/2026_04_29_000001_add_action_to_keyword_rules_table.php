<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keyword_rules', function (Blueprint $table) {
            // action: reply (default), escalate, ignore
            $table->string('action')->default('reply')
                ->comment('reply=send template, escalate=hand to human, ignore=skip message')
                ->after('reply_template');
        });
    }

    public function down(): void
    {
        Schema::table('keyword_rules', function (Blueprint $table) {
            $table->dropColumn('action');
        });
    }
};

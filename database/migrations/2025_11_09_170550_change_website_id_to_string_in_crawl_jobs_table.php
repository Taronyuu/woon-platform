<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crawl_jobs', function (Blueprint $table) {
            $table->dropForeign(['website_id']);
            $table->string('website_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('crawl_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('website_id')->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }
};

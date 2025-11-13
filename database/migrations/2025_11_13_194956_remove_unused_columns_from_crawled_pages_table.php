<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crawled_pages', function (Blueprint $table) {
            $table->dropColumn(['content', 'metadata', 'links']);
        });
    }

    public function down(): void
    {
        Schema::table('crawled_pages', function (Blueprint $table) {
            $table->longText('content')->nullable();
            $table->json('metadata')->nullable();
            $table->json('links')->nullable();
        });
    }
};

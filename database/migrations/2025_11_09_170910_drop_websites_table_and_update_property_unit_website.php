<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_unit_website', function (Blueprint $table) {
            $table->dropForeign(['website_id']);
            $table->dropUnique(['property_unit_id', 'website_id']);
            $table->dropIndex(['website_id', 'external_id']);
        });

        Schema::table('property_unit_website', function (Blueprint $table) {
            $table->string('website_id')->change();
            $table->unique(['property_unit_id', 'website_id']);
            $table->index(['website_id', 'external_id']);
        });

        Schema::dropIfExists('websites');
    }

    public function down(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('base_url');
            $table->string('crawler_class');
            $table->json('start_urls')->nullable();
            $table->integer('max_depth')->default(3);
            $table->integer('delay_ms')->default(1000);
            $table->boolean('use_flaresolverr')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('property_unit_website', function (Blueprint $table) {
            $table->dropUnique(['property_unit_id', 'website_id']);
            $table->dropIndex(['website_id', 'external_id']);
        });

        Schema::table('property_unit_website', function (Blueprint $table) {
            $table->unsignedBigInteger('website_id')->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->unique(['property_unit_id', 'website_id']);
            $table->index(['website_id', 'external_id']);
        });
    }
};

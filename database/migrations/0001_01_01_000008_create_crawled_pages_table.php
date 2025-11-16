<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crawled_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crawl_job_id')->constrained()->cascadeOnDelete();
            $table->text('url');
            $table->string('url_hash', 64)->index();
            $table->longText('raw_html')->nullable();
            $table->integer('status_code')->nullable();
            $table->string('content_hash', 64)->nullable();
            $table->string('mime_type')->default('text/html');
            $table->timestamp('scraped_at')->nullable();
            $table->timestamps();

            $table->unique(['crawl_job_id', 'url_hash']);
            $table->index(['crawl_job_id', 'status_code']);
            $table->index('content_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawled_pages');
    }
};

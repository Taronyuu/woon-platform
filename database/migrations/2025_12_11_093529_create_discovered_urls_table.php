<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discovered_urls', function (Blueprint $table) {
            $table->id();
            $table->string('website_id')->index();
            $table->text('url');
            $table->string('url_hash', 64);
            $table->enum('status', ['pending', 'fetched', 'failed'])->default('pending');
            $table->timestamp('discovered_at');
            $table->timestamp('last_fetched_at')->nullable();
            $table->unsignedTinyInteger('fail_count')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(['website_id', 'url_hash']);
            $table->index(['website_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discovered_urls');
    }
};

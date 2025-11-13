<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_units', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::table('property_units', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};

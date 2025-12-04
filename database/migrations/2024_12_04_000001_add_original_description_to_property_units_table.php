<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_units', function (Blueprint $table) {
            $table->longText('original_description')->nullable()->after('description');
        });

        DB::statement('UPDATE property_units SET original_description = description, description = NULL WHERE description IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('UPDATE property_units SET description = original_description WHERE original_description IS NOT NULL AND description IS NULL');

        Schema::table('property_units', function (Blueprint $table) {
            $table->dropColumn('original_description');
        });
    }
};

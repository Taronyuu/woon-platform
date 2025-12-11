<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE property_units MODIFY COLUMN status ENUM('available', 'reserved', 'unavailable') DEFAULT 'available'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE property_units MODIFY COLUMN status ENUM('available', 'sold', 'rented', 'pending', 'withdrawn') DEFAULT 'available'");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE property_units SET status = 'unavailable' WHERE status IN ('sold', 'rented', 'withdrawn')");
        DB::statement("UPDATE property_units SET status = 'reserved' WHERE status = 'pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE property_units SET status = 'available' WHERE status IN ('available', 'reserved', 'unavailable')");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $dutchCities = DB::table('cities')
            ->join('states', 'cities.state_id', '=', 'states.id')
            ->where('cities.country_code', 'NL')
            ->whereNotIn('states.name', ['Bonaire', 'Saba', 'Sint Eustatius'])
            ->select('cities.name', 'states.name as province')
            ->orderBy('cities.name')
            ->get();

        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('timezones');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('languages');

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('province')->nullable();
            $table->timestamps();
        });

        foreach ($dutchCities as $city) {
            DB::table('cities')->insert([
                'name' => $city->name,
                'province' => $city->province,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};

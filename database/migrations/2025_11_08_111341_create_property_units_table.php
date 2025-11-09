<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();

            $table->enum('property_type', [
                'house', 'apartment', 'villa', 'townhouse',
                'farm', 'commercial', 'land', 'parking', 'other'
            ]);

            $table->enum('transaction_type', ['sale', 'rent', 'auction']);

            $table->enum('status', [
                'available', 'sold', 'rented', 'pending', 'withdrawn'
            ])->default('available');

            $table->enum('living_type', [
                'woonhuis', 'appartement', 'studio', 'penthouse',
                'bovenwoning', 'benedenwoning', 'maisonnette', 'villa',
                'herenhuis', 'drive_in_woning', 'flat', 'galerij_flat'
            ])->nullable();

            $table->decimal('buyprice', 12, 2)->nullable();
            $table->string('buyprice_label')->nullable();
            $table->decimal('buyprice_range_from', 12, 2)->nullable();
            $table->decimal('buyprice_range_to', 12, 2)->nullable();
            $table->decimal('land_costs', 12, 2)->nullable();
            $table->decimal('contract_price', 12, 2)->nullable();
            $table->decimal('ground_lease', 12, 2)->nullable();
            $table->decimal('canon', 12, 2)->nullable();
            $table->text('canon_comment')->nullable();

            $table->decimal('rentprice_month', 10, 2)->nullable();
            $table->decimal('service_fee_month', 10, 2)->nullable();
            $table->decimal('total_rent_month', 10, 2)->nullable();

            $table->string('price_currency', 3)->default('EUR');
            $table->decimal('property_tax', 10, 2)->nullable();
            $table->decimal('hoa_fees', 10, 2)->nullable();

            $table->string('address_street')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_addition')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_province')->nullable();
            $table->string('address_country', 2)->default('NL');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('municipality')->nullable();

            $table->integer('surface')->nullable();
            $table->integer('lotsize')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('outdoor_surface')->nullable();
            $table->string('planarea')->nullable();

            $table->tinyInteger('bedrooms')->nullable();
            $table->tinyInteger('sleepingrooms')->nullable();
            $table->tinyInteger('bathrooms')->nullable();
            $table->tinyInteger('floors')->nullable();

            $table->smallInteger('construction_year')->nullable();
            $table->smallInteger('renovation_year')->nullable();

            $table->enum('energy_label', [
                'A++++', 'A+++', 'A++', 'A+', 'A',
                'B', 'C', 'D', 'E', 'F', 'G'
            ])->nullable();
            $table->decimal('energy_index', 5, 2)->nullable();

            $table->string('floor')->nullable();
            $table->string('orientation')->nullable();

            $table->boolean('berth')->default(false);
            $table->boolean('garage')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_ac')->default(false);
            $table->boolean('has_alarm')->default(false);

            $table->json('parking_lots_data')->nullable();
            $table->json('storages_data')->nullable();
            $table->json('outdoor_spaces_data')->nullable();

            $table->json('images')->nullable();
            $table->json('videos')->nullable();
            $table->json('floor_plans')->nullable();
            $table->string('virtual_tour_url')->nullable();
            $table->string('brochure_url')->nullable();

            $table->string('agent_name')->nullable();
            $table->string('agent_company')->nullable();
            $table->string('agent_phone')->nullable();
            $table->string('agent_email')->nullable();
            $table->string('agent_logo_url')->nullable();

            $table->json('features')->nullable();
            $table->json('amenities')->nullable();
            $table->json('data')->nullable();

            $table->date('listing_date')->nullable();
            $table->string('acceptance_date')->nullable();
            $table->dateTime('viewing_date')->nullable();

            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');
            $table->timestamp('last_changed_at')->nullable();
            $table->timestamps();

            $table->index(['transaction_type', 'status']);
            $table->index(['address_city']);
            $table->index(['address_postal_code']);
            $table->index(['buyprice']);
            $table->index(['rentprice_month']);
            $table->index(['surface']);
            $table->index(['listing_date']);
            $table->index(['first_seen_at']);
            $table->index(['last_seen_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_units');
    }
};

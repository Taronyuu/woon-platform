<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->string('postal_code')->nullable()->after('address');
            $table->string('city')->nullable()->after('postal_code');
            $table->string('locale', 5)->default('nl')->after('city');
            $table->string('type')->default('consumer')->after('locale');
            $table->boolean('notify_new_properties')->default(true)->after('type');
            $table->boolean('notify_price_changes')->default(true)->after('notify_new_properties');
            $table->boolean('notify_newsletter')->default(false)->after('notify_price_changes');
            $table->boolean('notify_marketing')->default(false)->after('notify_newsletter');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'address',
                'postal_code',
                'city',
                'locale',
                'type',
                'notify_new_properties',
                'notify_price_changes',
                'notify_newsletter',
                'notify_marketing',
            ]);
            $table->string('name')->after('id');
        });
    }
};

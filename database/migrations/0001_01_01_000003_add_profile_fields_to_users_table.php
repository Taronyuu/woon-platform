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
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('locale', 5)->default('nl')->after('phone');
            $table->string('type')->default('consumer')->after('locale');
            $table->boolean('notify_new_listings')->default(true)->after('type');
            $table->boolean('notify_price_changes')->default(true)->after('notify_new_listings');
            $table->boolean('notify_favorites')->default(true)->after('notify_price_changes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'locale',
                'type',
                'notify_new_listings',
                'notify_price_changes',
                'notify_favorites',
            ]);
            $table->string('name')->after('id');
        });
    }
};

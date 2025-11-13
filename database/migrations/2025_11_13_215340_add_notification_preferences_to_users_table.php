<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_new_properties')->default(true);
            $table->boolean('notify_price_changes')->default(true);
            $table->boolean('notify_newsletter')->default(false);
            $table->boolean('notify_marketing')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_new_properties',
                'notify_price_changes',
                'notify_newsletter',
                'notify_marketing',
            ]);
        });
    }
};

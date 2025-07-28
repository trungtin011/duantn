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
        Schema::table('platform_revenues', function (Blueprint $table) {
            $table->index('shop_id');
            $table->index('commission_amount');
            $table->index('total_amount');
            $table->index('net_revenue');
        });
    }

    public function down(): void
    {
        Schema::table('platform_revenues', function (Blueprint $table) {
            $table->dropIndex(['shop_id']);
            $table->dropIndex(['commission_amount']);
            $table->dropIndex(['total_amount']);
            $table->dropIndex(['net_revenue']);
        });
    }
}; 
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
        Schema::table('shop_order', function (Blueprint $table) {
            $table->decimal('shipping_shop_fee', 12, 2)->nullable()->after('total_order_amount');
            $table->decimal('discount_shop_amount', 12, 2)->nullable()->after('shipping_shop_fee');
        });
    }

    public function down(): void
    {
        Schema::table('shop_order', function (Blueprint $table) {
            $table->dropColumn(['shipping_shop_fee', 'discount_shop_amount']);
        });
    }
};

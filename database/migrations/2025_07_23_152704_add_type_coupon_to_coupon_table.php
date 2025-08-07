<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupon', function (Blueprint $table) {
            $table->enum('type_coupon', ['shipping', 'order', 'first_order', 'referral', 'other'])
            ->default('order')
            ->after('discount_type');
        });
    }

    public function down(): void
    {
        Schema::table('coupon', function (Blueprint $table) {
            $table->dropColumn('type_coupon');
        });
    }
};

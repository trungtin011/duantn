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
        Schema::table('ads_campaigns', function (Blueprint $table) {
            $table->decimal('bid_amount', 10, 2)->default(1.00)->after('status'); // Giá thầu tối thiểu 1đ
            $table->integer('impressions')->default(0)->after('bid_amount'); // Số lần hiển thị
            $table->integer('clicks')->default(0)->after('impressions'); // Số lần click
            $table->decimal('total_spent', 12, 2)->default(0.00)->after('clicks'); // Tổng tiền đã chi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads_campaigns', function (Blueprint $table) {
            $table->dropColumn(['bid_amount', 'impressions', 'clicks', 'total_spent']);
        });
    }
};

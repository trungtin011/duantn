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
        Schema::table('ad_clicks', function (Blueprint $table) {
            // Thêm cột user_id để track theo user
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // Thêm cột clicked_at để track thời gian click
            $table->timestamp('clicked_at')->nullable()->after('user_agent');
            
            // Thêm index để tối ưu query
            $table->index(['user_id', 'ads_campaign_id', 'shop_id']);
            $table->index(['user_ip', 'ads_campaign_id', 'shop_id', 'clicked_at']);
            
            // Thêm foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_clicks', function (Blueprint $table) {
            // Xóa foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Xóa index
            $table->dropIndex(['user_id', 'ads_campaign_id', 'shop_id']);
            $table->dropIndex(['user_ip', 'ads_campaign_id', 'shop_id', 'clicked_at']);
            
            // Xóa cột
            $table->dropColumn(['user_id', 'clicked_at']);
        });
    }
};

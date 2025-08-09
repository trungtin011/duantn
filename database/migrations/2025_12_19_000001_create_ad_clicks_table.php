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
        Schema::create('ad_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('ads_campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('click_type'); // 'shop_detail', 'product_detail', 'modal_view'
            $table->string('user_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->decimal('cost_per_click', 10, 2)->default(1000); // 1000 VND per click
            $table->boolean('is_charged')->default(false);
            $table->foreignId('wallet_transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['shop_id', 'created_at']);
            $table->index(['ads_campaign_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_clicks');
    }
};

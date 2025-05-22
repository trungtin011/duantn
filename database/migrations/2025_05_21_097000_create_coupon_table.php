<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('discount_value', 12, 2);
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('max_uses_per_user')->nullable();
            $table->integer('max_uses_total')->nullable();
            $table->integer('used_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('rank_limit', ['gold', 'silver', 'bronze', 'diamond', 'all'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired', 'deleted'])->default('active');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            
            $table->index('code');
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
        }); 
        Schema::create('coupon_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['available', 'used', 'expired'])->default('available');
            $table->timestamp('used_at')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

            $table->unique(['coupon_id', 'user_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon');
    }
};

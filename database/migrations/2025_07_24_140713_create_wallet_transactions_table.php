<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_wallet_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('direction', ['in', 'out']);
            $table->enum('type', ['order', 'withdraw', 'adjustment', 'refund']);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('completed');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

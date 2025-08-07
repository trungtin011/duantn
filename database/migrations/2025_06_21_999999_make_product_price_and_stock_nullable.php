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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('purchase_price', 12, 0)->nullable()->change();
            $table->decimal('sale_price', 12, 0)->nullable()->change();
            $table->integer('sold_quantity')->nullable()->change();
            $table->integer('stock_total')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('purchase_price', 12, 0)->nullable(false)->change();
            $table->decimal('sale_price', 12, 0)->nullable(false)->change();
            $table->integer('sold_quantity')->nullable(false)->change();
            $table->integer('stock_total')->nullable(false)->change();
        });
    }
}; 
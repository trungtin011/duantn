<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items_order', function (Blueprint $table) {
            $table->unsignedBigInteger('variantID')->nullable()->change();
            $table->dropColumn('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_order', function (Blueprint $table) {
            $table->unsignedBigInteger('variantID')->nullable(false)->change();
            $table->decimal('discount_amount', 10, 2)->nullable(false)->change();
        });
    }
};

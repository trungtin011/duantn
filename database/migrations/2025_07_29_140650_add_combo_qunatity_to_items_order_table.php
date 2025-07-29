<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('items_order', function (Blueprint $table) {
            $table->integer('combo_quantity')->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('items_order', function (Blueprint $table) {
            $table->dropColumn('combo_quantity');
        });
    }
};

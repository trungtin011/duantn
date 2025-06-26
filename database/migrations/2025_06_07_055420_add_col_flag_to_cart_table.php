<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->string('buying_flag')->default('0')->after('total_price');
        });
    }

    
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('buying_flag');
        });
    }
};

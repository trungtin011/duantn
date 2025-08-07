<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::table('shop_order', function (Blueprint $table) {
            $table->boolean('is_revenue_transferred')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('shop_order', function (Blueprint $table) {
            $table->dropColumn('is_revenue_transferred');
        });
    }
};

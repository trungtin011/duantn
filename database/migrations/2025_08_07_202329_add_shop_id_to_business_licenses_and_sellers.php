<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('business_licenses', function (Blueprint $table) {
        $table->unsignedBigInteger('shop_id')->nullable()->after('id');
        $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
    });
    Schema::table('sellers', function (Blueprint $table) {
        $table->unsignedBigInteger('shop_id')->nullable()->after('id');
        $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('business_licenses', function (Blueprint $table) {
        $table->dropForeign(['shop_id']);
        $table->dropColumn('shop_id');
    });
    Schema::table('sellers', function (Blueprint $table) {
        $table->dropForeign(['shop_id']);
        $table->dropColumn('shop_id');
    });
}
};

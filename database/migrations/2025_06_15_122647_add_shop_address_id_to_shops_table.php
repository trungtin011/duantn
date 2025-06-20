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
        Schema::table('shops', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_address_id')->nullable()->after('id');

            // Nếu muốn thêm khóa ngoại:
            // $table->foreign('shop_address_id')->references('id')->on('shop_addresses')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('shop_address_id');
        });
    }
};

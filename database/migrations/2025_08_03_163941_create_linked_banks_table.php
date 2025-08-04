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
    Schema::create('linked_banks', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('seller_id');
        $table->unsignedBigInteger('bank_id');
        $table->string('account_number');
        $table->string('account_name');
        $table->timestamps();

        $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
        $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linked_banks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active');
            $table->bigInteger('identity_card')->unique();
            $table->date('identity_card_date');
            $table->string('identity_card_place');
            $table->string('bank_account')->unique();
            $table->string('bank_name');
            $table->string('bank_account_name');
            $table->unsignedBigInteger('business_license_id');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('business_license_id')->references('id')->on('business_licenses')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
}; 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 11);
            $table->string('address',255);
            $table->string('province',100);
            $table->string('district',100);
            $table->string('ward',100);
            $table->string('zip_code',10);
            $table->enum('address_type', ['home', 'office', 'other'])->default('home');
            $table->text('note')->nullable();
            $table->boolean('is_default')->default(false);
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
}; 
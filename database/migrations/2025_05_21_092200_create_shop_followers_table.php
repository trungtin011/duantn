<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_followers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopID');
            $table->unsignedBigInteger('followerID');
            $table->boolean('notifications_enabled')->default(true);
            $table->timestamp('followed_at')->useCurrent();
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('followerID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['shopID', 'followerID']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('shop_followers');
    }
}; 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('receiver_user_id')->nullable();
            $table->unsignedBigInteger('receiver_shop_id')->nullable();
            $table->string('title', 100);
            $table->text('content');
            $table->string('type', 100);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->enum('status', ['unread', 'read', 'archived'])->default('unread');
            $table->enum('receiver_type', ['user', 'shop']);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_shop_id')->references('id')->on('shops')->onDelete('cascade');

            $table->index('type');
            $table->index('status');
            $table->index('priority');
            $table->index('receiver_type');
            $table->index('created_at');
            $table->index(['receiver_user_id', 'status']);
            $table->index(['receiver_shop_id', 'status']);
        });

 

    }

 
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};

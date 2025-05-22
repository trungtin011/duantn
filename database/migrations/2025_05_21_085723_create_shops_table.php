<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ownerID');
            $table->string('shop_name',100);
            $table->string('shop_phone',11);
            $table->string('shop_email',100);
            $table->text('shop_description');
            $table->decimal('shop_rating', 3, 2)->default(0);
            $table->integer('total_ratings')->default(0);
            $table->integer('total_products')->default(0);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->integer('total_followers')->default(0);
            $table->json('opening_hours')->nullable();
            $table->json('social_media_links')->nullable();
            $table->text('shop_logo');
            $table->text('shop_banner');
            $table->enum('shop_status', ['active', 'inactive','banned'])->default('active');
            $table->timestamps();

            $table->foreign('ownerID')->references('id')->on('users')->onDelete('cascade');
            $table->index('shop_status');
            $table->index('shop_rating');
        });

        Schema::create('shop_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopID');
            $table->string('shop_address',255);
            $table->string('shop_province',100);
            $table->string('shop_district',100);
            $table->string('shop_ward',100);
            $table->text('note')->nullable();
            $table->boolean('is_default')->default(false);
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->timestamps();
        });

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
        Schema::dropIfExists('shops');
        Schema::dropIfExists('shop_addresses');
        Schema::dropIfExists('shop_followers'); 
    }
};

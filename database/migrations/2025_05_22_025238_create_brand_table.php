<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brand', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('slug',100);
            $table->text('description');
            $table->text('image_path');

            $table->string('meta_title',255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords',255)->nullable();

            $table->enum('status',['active','inactive','deleted']);
            $table->timestamps();

            $table->index('name');
            $table->index('slug');
        });

        Schema::create('sub_brand', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brandID');
            $table->string('name',100);
            $table->string('slug',100);
            $table->text('description');
            $table->text('image_path');

            $table->string('meta_title',255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords',255)->nullable();

            $table->enum('status',['active','inactive','deleted']);
            $table->foreign('brandID')->references('id')->on('brand')->onDelete('cascade');

            $table->timestamps();

            $table->index('name');
            $table->index('slug');
        }); 

    }


    public function down(): void
    {
        Schema::dropIfExists('brand');
        Schema::dropIfExists('sub_brand');
    }
};

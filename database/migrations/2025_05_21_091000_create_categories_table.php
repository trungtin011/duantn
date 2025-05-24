<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
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
    }
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
}; 
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
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->text('image_path')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->unsignedBigInteger('parent_id')->nullable(); // Thêm cột parent_id
            $table->timestamps();

            $table->index('name');
            $table->index('slug');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade'); // Khóa ngoại
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

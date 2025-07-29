<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('review_likes', function (Blueprint $table) {
            $table->foreignId('order_review_id')->constrained('order_reviews')->onDelete('cascade');
            $table->unique(['user_id', 'order_review_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_likes');
    }
};

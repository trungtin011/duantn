<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->enum('ranking', ['gold','silver','bronze','diamond'])->default('bronze');
            $table->enum('preferred_payment_method', ['cod', 'momo', 'zalopay'])->nullable();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 0)->default(0);
            $table->integer('total_points')->default(0);
            $table->timestamp('last_order_at')->nullable();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
}; 
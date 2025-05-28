<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopID');
            $table->unsignedBigInteger('userID');
            $table->string('position', 50);
            $table->decimal('salary', 12, 2);
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();

            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['shopID', 'userID']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
}; 
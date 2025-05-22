<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username',50)->unique();
            $table->string('fullname',100);
            $table->string('phone',11)->unique();
            $table->string('email',100)->unique();
            $table->string('password');
            $table->enum('status', ['active', 'inactive','banned'])->default('active');
            $table->enum('gender', ['male', 'female','other']); 
            $table->enum('role', ['admin', 'customer','seller','employee']);
            $table->text('avatar')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->date('birthday')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->index('email');
            $table->index('phone');
            $table->index('username');
            $table->index('status');
            $table->index('role');
        });
        
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}; 
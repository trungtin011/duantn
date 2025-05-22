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

        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 11);
            $table->string('address',255);
            $table->string('province',100);
            $table->string('district',100);
            $table->string('ward',100);
            $table->string('zip_code',10);
            $table->enum('address_type', ['home', 'office', 'other'])->default('home');
            $table->text('note')->nullable();
            $table->boolean('is_default')->default(false);
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

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

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->enum('position', ['admin','manager','staff']);
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->decimal('salary', 12,0);
            $table->unsignedBigInteger('shopID');
            $table->date('hired_date');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active');
            $table->bigInteger('identity_card')->unique();
            $table->date('identity_card_date');
            $table->string('identity_card_place');
            $table->string('bank_account')->unique();
            $table->string('bank_name');
            $table->string('bank_account_name');
            $table->unsignedBigInteger('business_license_id');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('business_license_id')->references('id')->on('business_licenses')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('business_licenses', function (Blueprint $table) { 
            $table->id();
            $table->string('business_license_number')->unique();
            $table->string('tax_number')->unique();
            $table->string('business_ID')->unique();
            $table->string('business_name');
            $table->date('business_license_date');
            $table->date('expiry_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('license_file_path');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

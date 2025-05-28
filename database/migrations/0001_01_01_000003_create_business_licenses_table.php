<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }
    public function down(): void
    {
        Schema::dropIfExists('business_licenses');
    }
}; 
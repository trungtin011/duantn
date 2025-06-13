<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userID');
            $table->string('full_name', 100);
            $table->string('identity_number', 20)->unique();
            $table->date('birth_date');
            $table->string('nationality', 100)->default('Vietnam');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('hometown', 255);
            $table->string('residence', 255);
            $table->enum('identity_type', ['cccd', 'cmnd'])->default('cccd');
            $table->date('identity_card_date');
            $table->string('identity_card_place', 255);
            $table->text('identity_card_image');
            $table->text('identity_card_holding_image');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'identity_number']);
        });

        // ĐÃ BỎ CHÈN DỮ LIỆU MẪU userID = 1 ĐỂ TRÁNH LỖI KHOÁ NGOẠI
        // Nếu cần dữ liệu mẫu, hãy sử dụng Seeder sau khphpi user id=1 đã tồn tại
    }

    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
    }
};

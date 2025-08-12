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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique(); // Mã ticket duy nhất
            $table->unsignedBigInteger('user_id'); // Người tạo ticket
            $table->unsignedBigInteger('assigned_to')->nullable(); // Admin được phân công
            $table->string('subject'); // Tiêu đề ticket
            $table->text('description'); // Mô tả chi tiết
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'waiting_for_customer', 'resolved', 'closed'])->default('open');
            $table->enum('category', ['technical', 'billing', 'general', 'bug_report', 'feature_request', 'other'])->default('general');
            $table->string('attachment_path')->nullable(); // Đường dẫn file đính kèm
            $table->timestamp('resolved_at')->nullable(); // Thời gian giải quyết
            $table->timestamp('closed_at')->nullable(); // Thời gian đóng ticket
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'priority', 'category']);
            $table->index('ticket_code');
        });

        // Bảng ticket replies để lưu các phản hồi
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id'); // Người trả lời
            $table->text('message'); // Nội dung phản hồi
            $table->string('attachment_path')->nullable(); // File đính kèm
            $table->boolean('is_internal')->default(false); // Ghi chú nội bộ (chỉ admin thấy)
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
    }
};

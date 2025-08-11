<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm type 'advertising' vào enum hiện tại
        DB::statement("ALTER TABLE wallet_transactions MODIFY type ENUM('revenue', 'reverse_revenue', 'withdraw', 'advertising') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa type 'advertising' khỏi enum
        DB::statement("ALTER TABLE wallet_transactions MODIFY type ENUM('revenue', 'reverse_revenue', 'withdraw') NOT NULL");
    }
};

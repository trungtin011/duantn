<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm giá trị 'suspended' vào enum shop_status
        DB::statement("ALTER TABLE shops MODIFY shop_status ENUM('active', 'inactive', 'banned', 'suspended') DEFAULT 'active'");
    }

    public function down(): void
    {
        // Nếu rollback, loại bỏ 'suspended'
        DB::statement("ALTER TABLE shops MODIFY shop_status ENUM('active', 'inactive', 'banned') DEFAULT 'active'");
    }
};

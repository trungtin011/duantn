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
        // Cập nhật enum status để thêm 'pending' và 'inactive'
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'out_of_stock', 'deleted')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quay lại enum status ban đầu
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active', 'out_of_stock', 'deleted')");
    }
};

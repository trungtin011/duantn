<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE shop_order MODIFY COLUMN status ENUM('pending', 'confirmed', 'ready_to_pick', 'picked', 'shipping', 'delivered', 'cancelled', 'shipping_failed', 'returned', 'completed', 'damage', 'lost') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE shop_order MODIFY COLUMN status ENUM('pending', 'confirmed', 'ready_to_pick', 'picked', 'shipping', 'delivered', 'cancelled', 'shipping_failed', 'returned', 'completed') DEFAULT 'pending'");
    }
};

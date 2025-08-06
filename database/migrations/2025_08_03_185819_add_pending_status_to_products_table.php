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
        Schema::table('products', function (Blueprint $table) {
            // Thay đổi enum status để thêm 'pending'
            $table->enum('status', ['active', 'inactive', 'pending', 'out_of_stock', 'deleted'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Quay lại enum status ban đầu
            $table->enum('status', ['active', 'out_of_stock', 'deleted'])->change();
        });
    }
}; 
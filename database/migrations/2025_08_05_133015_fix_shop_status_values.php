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
        // Cập nhật các giá trị shop_status không hợp lệ thành 'inactive'
        DB::table('shops')
            ->whereNull('shop_status')
            ->orWhere('shop_status', '')
            ->orWhereNotIn('shop_status', ['active', 'inactive', 'banned'])
            ->update(['shop_status' => 'inactive']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì đây là dữ liệu cần sửa
    }
};

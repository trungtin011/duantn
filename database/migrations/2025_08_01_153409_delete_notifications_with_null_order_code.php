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
        // Xóa tất cả notifications có type = 'order' và order_code = null
        DB::table('notifications')
            ->where('type', 'order')
            ->whereNull('order_code')
            ->delete();
            
        // Cũng xóa các notification_receiver records liên quan
        DB::table('notification_receiver')
            ->whereIn('notification_id', function($query) {
                $query->select('id')
                      ->from('notifications')
                      ->where('type', 'order')
                      ->whereNull('order_code');
            })
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không thể khôi phục dữ liệu đã xóa
        // Migration này chỉ xóa dữ liệu, không tạo lại
    }
};

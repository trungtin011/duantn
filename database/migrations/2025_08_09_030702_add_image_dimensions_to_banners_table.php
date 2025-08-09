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
        Schema::table('banners', function (Blueprint $table) {
            // Thêm các trường cho kích thước hình ảnh
            if (!Schema::hasColumn('banners', 'image_width')) {
                $table->integer('image_width')->nullable();
            }
            if (!Schema::hasColumn('banners', 'image_height')) {
                $table->integer('image_height')->nullable();
            }
            if (!Schema::hasColumn('banners', 'image_size')) {
                $table->string('image_size')->nullable(); // Kích thước file (KB, MB)
            }
            
            // Các trường font-size đã được thêm ở migration 2025_08_09_022946_add_font_size_fields_to_banners_table
            // Không thêm lại tại đây để tránh trùng cột

            // Thêm trường cho responsive
            if (!Schema::hasColumn('banners', 'responsive_settings')) {
                $table->json('responsive_settings')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['image_width','image_height','image_size','responsive_settings'] as $col) {
                if (Schema::hasColumn('banners', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

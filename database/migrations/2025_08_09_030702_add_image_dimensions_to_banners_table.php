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
            $table->integer('image_width')->nullable();
            $table->integer('image_height')->nullable();
            $table->string('image_size')->nullable(); // Kích thước file (KB, MB)
            
            // Thêm các trường cho tùy chỉnh hiển thị
            $table->string('content_position')->default('center');
            $table->string('text_align')->default('center');
            $table->string('title_color')->default('#ffffff');
            $table->string('subtitle_color')->default('#f3f4f6');
            $table->string('title_font_size')->default('2rem');
            $table->string('subtitle_font_size')->default('1rem');
            
            // Thêm trường cho responsive
            $table->json('responsive_settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'image_width',
                'image_height', 
                'image_size',
                'content_position',
                'text_align',
                'title_color',
                'subtitle_color',
                'title_font_size',
                'subtitle_font_size',
                'responsive_settings'
            ]);
        });
    }
};

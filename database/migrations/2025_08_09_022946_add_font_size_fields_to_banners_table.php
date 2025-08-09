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
            if (!Schema::hasColumn('banners', 'title_font_size')) {
                $table->string('title_font_size')->default('2rem')->after('title_color');
            }
            if (!Schema::hasColumn('banners', 'subtitle_font_size')) {
                $table->string('subtitle_font_size')->default('1rem')->after('subtitle_color');
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
            foreach (['title_font_size', 'subtitle_font_size'] as $col) {
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

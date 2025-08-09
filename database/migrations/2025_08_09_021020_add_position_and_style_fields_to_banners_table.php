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
            if (!Schema::hasColumn('banners', 'content_position')) {
                $table->string('content_position')->default('center')->after('link_url');
            }
            if (!Schema::hasColumn('banners', 'text_align')) {
                $table->string('text_align')->default('center')->after('content_position');
            }
            if (!Schema::hasColumn('banners', 'title_color')) {
                $table->string('title_color')->default('#ffffff')->after('text_align');
            }
            if (!Schema::hasColumn('banners', 'subtitle_color')) {
                $table->string('subtitle_color')->default('#f3f4f6')->after('title_color');
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
            foreach (['content_position', 'text_align', 'title_color', 'subtitle_color'] as $col) {
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

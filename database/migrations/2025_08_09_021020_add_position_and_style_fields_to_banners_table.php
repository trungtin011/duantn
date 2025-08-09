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
            $table->string('content_position')->default('center')->after('link_url');
            $table->string('text_align')->default('center')->after('content_position');
            $table->string('title_color')->default('#ffffff')->after('text_align');
            $table->string('subtitle_color')->default('#f3f4f6')->after('title_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['content_position', 'text_align', 'title_color', 'subtitle_color']);
        });
    }
};

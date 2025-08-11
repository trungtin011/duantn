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
            $table->string('image_position')->default('center')->after('image_size');
            $table->string('image_object_fit')->default('cover')->after('image_position');
            $table->string('image_object_position')->default('center')->after('image_object_fit');
            $table->boolean('image_parallax')->default(false)->after('image_object_position');
            $table->decimal('image_scale', 3, 2)->default(1.00)->after('image_parallax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'image_position',
                'image_object_fit', 
                'image_object_position',
                'image_parallax',
                'image_scale'
            ]);
        });
    }
};

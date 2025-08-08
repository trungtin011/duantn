<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trong file migration
        Schema::table('linked_banks', function (Blueprint $table) {
            $table->boolean('is_default')->default(false);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('linked_banks', function (Blueprint $table) {
            //
        });
    }
};

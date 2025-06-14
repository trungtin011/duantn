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

        Schema::table('notification', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'inactive', 'failed'])->default('pending')->change();
            $table->enum('is_read', ['read', 'unread'])->default('unread');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('is_read');
        });
    }
};

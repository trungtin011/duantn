<?php
// database/migrations/2025_06_12_100000_add_image_url_to_shop_qa_messages.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shop_qa_messages', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('message');
        });
    }
    public function down(): void
    {
        Schema::table('shop_qa_messages', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
};

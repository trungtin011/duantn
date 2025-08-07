
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRankAndTotalSpentToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rank', ['iron', 'bronze', 'silver', 'gold', 'diamond', 'supreme'])->default('iron')->after('password');
            $table->decimal('total_spent', 15, 2)->default(0)->after('rank');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rank', 'total_spent']);
        });
    }
}


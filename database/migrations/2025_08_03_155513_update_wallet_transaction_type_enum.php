<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        DB::statement("ALTER TABLE wallet_transactions MODIFY type ENUM('withdraw', 'deposit', 'revenue') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE wallet_transactions MODIFY type ENUM('withdraw', 'deposit') NOT NULL");
    }
};

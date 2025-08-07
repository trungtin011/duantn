<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsTable extends Migration
{

    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');

            $table->string('provider');              // momo, vnpay, ...
            $table->string('method')->nullable();    // QR, ATM, VISA...

            $table->decimal('amount', 15, 2);        // số tiền giao dịch
            $table->string('currency', 10)->default('VND');

            $table->string('status');                // success, failed, pending...

            $table->string('transaction_id')->nullable();  // mã giao dịch từ MoMo/VNPay
            $table->text('raw_response')->nullable();      // lưu dữ liệu JSON/query string gốc
            $table->string('message')->nullable();         // thông báo từ cổng thanh toán

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
}

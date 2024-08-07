<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundIdToRefundTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('refund_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_transactions', function (Blueprint $table) {
            $table->dropColumn('refund_id');
        });
    }
}

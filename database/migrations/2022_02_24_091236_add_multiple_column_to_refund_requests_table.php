<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnToRefundRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->longText('approved_note')->nullable();
            $table->longText('rejected_note')->nullable();
            $table->longText('payment_info')->nullable();
            $table->string('change_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropColumn('approved_note');
            $table->dropColumn('rejected_note');
            $table->dropColumn('payment_info');
            $table->dropColumn('change_by');
        });
    }
}

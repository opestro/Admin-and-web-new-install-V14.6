<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliverymanidColumnToWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->bigInteger('delivery_man_id')->after('seller_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->dropColumn('delivery_man_id')->after('seller_id')->nullable();
        });
    }
}

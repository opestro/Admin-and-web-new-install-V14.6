<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SellerWalletWithdrawBal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_wallets', function (Blueprint $table) {
            $table->string('withdrawn')->change();
            $table->string('balance')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_wallets', function (Blueprint $table) {
            //
        });
    }
}

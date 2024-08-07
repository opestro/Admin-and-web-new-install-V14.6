<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToSellerWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_wallets', function (Blueprint $table) {
            $table->float('commission_given')->default(0);
            $table->float('total_earning')->default(0);
            $table->float('pending_withdraw')->default(0);
            $table->float('total_withdraw')->default(0);
            $table->float('delivery_charge_earned')->default(0);
            $table->float('collected_cash')->default(0);
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

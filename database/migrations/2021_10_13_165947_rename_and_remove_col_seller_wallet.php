<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAndRemoveColSellerWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_wallets', function (Blueprint $table) {
            $table->dropColumn('total_earning');
            $table->renameColumn('balance', 'total_earning');
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

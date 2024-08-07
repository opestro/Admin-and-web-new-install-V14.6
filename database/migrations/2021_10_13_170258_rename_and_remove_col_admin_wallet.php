<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAndRemoveColAdminWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_wallets', function (Blueprint $table) {
            $table->dropColumn('inhouse_sell');
            $table->renameColumn('balance', 'inhouse_earning');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_wallets', function (Blueprint $table) {
            //
        });
    }
}

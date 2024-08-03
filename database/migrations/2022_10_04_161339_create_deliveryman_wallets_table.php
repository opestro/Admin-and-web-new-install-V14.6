<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliverymanWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryman_wallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('delivery_man_id');
            $table->decimal('current_balance', 50, 2)->default(0);
            $table->decimal('cash_in_hand', 50, 2)->default(0);
            $table->decimal('pending_withdraw', 50, 2)->default(0);
            $table->decimal('total_withdraw', 50, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveryman_wallets');
    }
}

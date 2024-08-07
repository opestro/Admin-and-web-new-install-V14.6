<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChengeOrderAmountSellerAmountAdminCommissionDeliveryChargeTaxToorderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_transactions', function (Blueprint $table) {
            $table->decimal('order_amount', 50, 2)->change();
            $table->decimal('seller_amount', 50, 2)->change();
            $table->decimal('admin_commission', 50, 2)->change();
            $table->decimal('delivery_charge', 50, 2)->change();
            $table->decimal('tax', 50, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

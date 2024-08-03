<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliverymanChargeAndExpectedDeliveryDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('deliveryman_charge',50)->default(0)->after('delivery_man_id');
            $table->date('expected_delivery_date')->nullable()->after('deliveryman_charge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('deliveryman_charge');
            $table->dropColumn('expected_delivery_date');
        });
    }
}

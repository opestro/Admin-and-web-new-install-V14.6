<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToOrderTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_transactions', function (Blueprint $table) {
            $table->bigInteger('customer_id')->nullable();
            $table->string('seller_is')->nullable('admin');
            $table->string('delivered_by')->default('admin');
            $table->string('payment_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_transactions', function (Blueprint $table) {
            //
        });
    }
}

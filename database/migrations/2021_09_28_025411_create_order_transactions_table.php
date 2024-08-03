<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('seller_id');
            $table->bigInteger('order_id');
            $table->decimal('order_amount')->default(0);
            $table->decimal('seller_amount')->default(0);
            $table->decimal('admin_commission')->default(0);
            $table->string('received_by');
            $table->string('status')->nullable();
            $table->decimal('delivery_charge')->default(0);
            $table->decimal('tax')->default(0);
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
        Schema::dropIfExists('order_transactions');
    }
}

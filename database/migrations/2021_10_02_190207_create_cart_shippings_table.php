<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_shippings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cart_group_id')->nullable();
            $table->bigInteger('shipping_method_id')->nullable();
            $table->float('shipping_cost')->default(0);
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
        Schema::dropIfExists('cart_shippings');
    }
}

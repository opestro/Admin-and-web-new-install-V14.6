<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourColumnToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float('shipping_cost')->nullable();
            $table->boolean('multiply_qty')->nullable();
            $table->float('temp_shipping_cost')->nullable();
            $table->boolean('is_shipping_cost_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shipping_cost');
            $table->dropColumn('multiply_qty');
            $table->dropColumn('temp_shipping_cost');
            $table->dropColumn('is_shipping_cost_updated');
        });
    }
}

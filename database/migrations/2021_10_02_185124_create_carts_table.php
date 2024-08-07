<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->nullable();
            $table->string('cart_group_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('color')->nullable();
            $table->text('choices')->nullable();
            $table->text('variations')->nullable();
            $table->text('variant')->nullable();
            $table->integer('quantity')->default(1);
            $table->float('price')->default(1);
            $table->float('tax')->default(1);
            $table->float('discount')->default(1);
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('thumbnail')->nullable();
            $table->bigInteger('seller_id')->nullable();
            $table->string('seller_is')->default('admin');
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
        Schema::dropIfExists('carts');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_followers', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id'); // Shop Table (Shop ID)
            $table->integer('user_id')->comment('Customer ID'); // User Table (Customer ID)
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
        Schema::dropIfExists('shop_followers');
    }
}

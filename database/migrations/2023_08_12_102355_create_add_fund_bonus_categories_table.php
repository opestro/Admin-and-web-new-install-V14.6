<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFundBonusCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_fund_bonus_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('bonus_type', 50); // percentage, flat
            $table->float('bonus_amount', 14, 2)->default(0);
            $table->float('min_add_money_amount', 14, 2)->default(0);
            $table->float('max_bonus_amount', 14, 2)->default(0);
            $table->dateTime('start_date_time')->nullable();
            $table->dateTime('end_date_time')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('add_fund_bonus_categories');
    }
}

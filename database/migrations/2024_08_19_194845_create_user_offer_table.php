<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_offer', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key column called 'id'
            $table->unsignedBigInteger('user_id'); // Defines 'user_id' as an unsigned big integer
            $table->unsignedBigInteger('offer_id'); // Defines 'offer_id' as an unsigned big integer
            $table->integer('credits'); // Defines 'credits' as an integer
            $table->integer('royals'); // Defines 'royals' as an integer
            $table->timestamps(); // Adds 'created_at' and 'updated_at' timestamp columns

            // Optionally, you might want to add foreign key constraints
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('offer_id')->references('id')->on('offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_offer');
    }
}

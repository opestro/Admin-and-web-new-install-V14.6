<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key column called 'id'
            $table->string('name')->nullable();
            $table->double('price')->default(0)->nullable();
            $table->integer('credits'); // Defines 'credits' as an integer
            $table->integer('royals'); // Defines 'royals' as an integer
            $table->timestamps(); // Adds 'created_at' and 'updated_at' timestamp columns

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}

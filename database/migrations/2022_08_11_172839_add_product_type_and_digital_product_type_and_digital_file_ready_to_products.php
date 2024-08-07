<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductTypeAndDigitalProductTypeAndDigitalFileReadyToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type', 20)->after('slug')->default('physical');
            $table->string('digital_product_type', 30)->after('refundable')->nullable();
            $table->string('digital_file_ready')->after('digital_product_type')->nullable();
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
            $table->dropColumn('product_type');
            $table->dropColumn('digital_product_type');
            $table->dropColumn('digital_file_ready');
        });
    }
}

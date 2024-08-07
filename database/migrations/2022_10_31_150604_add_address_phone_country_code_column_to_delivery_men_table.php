<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressPhoneCountryCodeColumnToDeliveryMenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_men', function (Blueprint $table) {
            $table->text('address')->after('l_name')->nullable();
            $table->string('country_code', 20)->after('address')->nullable();
            $table->tinyInteger('is_online')->default(1)->after('is_active');
            $table->dropUnique(['phone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_men', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('country_code');
            $table->dropColumn('is_online');
        });
    }
}

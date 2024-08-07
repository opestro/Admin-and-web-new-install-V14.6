<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankingColumnsToDeliveryMenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_men', function (Blueprint $table) {
            $table->string('holder_name')->nullable()->after('password');
            $table->string('account_no')->nullable()->after('password');
            $table->string('branch')->nullable()->after('password');
            $table->string('bank_name')->nullable()->after('password');
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
            $table->dropColumn('holder_name')->nullable();
            $table->dropColumn('account_no')->nullable();
            $table->dropColumn('branch')->nullable();
            $table->dropColumn('bank_name')->nullable();
        });
    }
}

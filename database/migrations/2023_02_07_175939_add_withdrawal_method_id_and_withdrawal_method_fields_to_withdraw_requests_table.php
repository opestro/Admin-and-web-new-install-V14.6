<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWithdrawalMethodIdAndWithdrawalMethodFieldsToWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->foreignId('withdrawal_method_id')->after('amount')->nullable();
            $table->json('withdrawal_method_fields')->after('withdrawal_method_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            Schema::dropIfExists('withdrawal_method_fields');
            Schema::dropIfExists('withdrawal_method_id');
        });
    }
}

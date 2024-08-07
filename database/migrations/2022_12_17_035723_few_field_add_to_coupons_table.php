<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FewFieldAddToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('added_by')->after('id')->default('admin');
            $table->string('coupon_bearer')->after('coupon_type')->default('inhouse');
            $table->bigInteger('seller_id')->after('coupon_bearer')->nullable()->comment('NULL=in-house, 0=all seller');
            $table->bigInteger('customer_id')->after('seller_id')->nullable()->comment('0 = all customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            Schema::dropIfExists('added_by');
            Schema::dropIfExists('coupon_bearer');
            Schema::dropIfExists('seller_id');
            Schema::dropIfExists('customer_id');
        });
    }
}

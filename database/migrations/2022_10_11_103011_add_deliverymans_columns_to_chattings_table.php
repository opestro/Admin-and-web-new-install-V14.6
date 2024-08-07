<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliverymansColumnsToChattingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chattings', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->change();
            $table->bigInteger('seller_id')->nullable()->change();
            $table->bigInteger('delivery_man_id')->nullable()->after('seller_id');
            $table->bigInteger('admin_id')->nullable()->after('seller_id');
            $table->boolean('sent_by_delivery_man')->nullable()->after('sent_by_seller');
            $table->boolean('sent_by_admin')->nullable()->after('sent_by_seller');
            $table->boolean('seen_by_delivery_man')->nullable()->after('seen_by_seller');
            $table->boolean('seen_by_admin')->nullable()->after('seen_by_seller');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chattings', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('seller_id');
            $table->dropColumn('delivery_man_id');
            $table->dropColumn('admin_id');
            $table->dropColumn('sent_by_delivery_man');
            $table->dropColumn('sent_by_admin');
            $table->dropColumn('seen_by_delivery_man');
            $table->dropColumn('seen_by_admin');
        });
    }
}

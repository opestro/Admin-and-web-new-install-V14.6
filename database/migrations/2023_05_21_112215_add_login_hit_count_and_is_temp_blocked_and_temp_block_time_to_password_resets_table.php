<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginHitCountAndIsTempBlockedAndTempBlockTimeToPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->tinyInteger('otp_hit_count')->default('0')->after('token');
            $table->boolean('is_temp_blocked')->default('0')->after('otp_hit_count');
            $table->timestamp('temp_block_time')->nullable()->after('is_temp_blocked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropColumn('otp_hit_count');
            $table->dropColumn('is_temp_blocked');
            $table->dropColumn('temp_block_time');
        });
    }
}

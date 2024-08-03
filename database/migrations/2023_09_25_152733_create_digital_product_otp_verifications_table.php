<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalProductOtpVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_product_otp_verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_details_id')->nullable();
            $table->string('identity')->nullable();
            $table->string('token')->nullable();
            $table->tinyInteger('otp_hit_count')->default('0');
            $table->boolean('is_temp_blocked')->default('0');
            $table->timestamp('temp_block_time')->nullable();
            $table->timestamp('expires_at')->nullable();
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
        Schema::dropIfExists('digital_product_otp_verifications');
    }
}

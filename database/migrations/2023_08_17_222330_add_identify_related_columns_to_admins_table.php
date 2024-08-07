<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentifyRelatedColumnsToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->text('identify_image')->nullable()->after('image');
            $table->string('identify_type')->nullable()->after('identify_image');
            $table->integer('identify_number')->nullable()->after('identify_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->text('identify_image');
            $table->string('identify_type');
            $table->integer('identify_number');
        });
    }
}

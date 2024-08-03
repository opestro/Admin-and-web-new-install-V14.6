<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategorySubCategoryAndSubSubCategoryAddInProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category_id')->after('category_ids')->nullable();
            $table->string('sub_category_id')->after('category_id')->nullable();
            $table->string('sub_sub_category_id')->after('sub_category_id')->nullable();
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
            Schema::dropIfExists('category_id');
            Schema::dropIfExists('sub_category_id');
            Schema::dropIfExists('sub_sub_category_id');
        });
    }
}

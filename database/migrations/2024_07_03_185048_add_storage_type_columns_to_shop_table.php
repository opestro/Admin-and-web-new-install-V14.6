<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('image_storage_type',10)->default('public')->after('image')->nullable();
            $table->string('banner_storage_type',10)->default('public')->after('banner')->nullable();
            $table->string('bottom_banner_storage_type',10)->default('public')->after('bottom_banner')->nullable();
            $table->string('offer_banner_storage_type',10)->default('public')->after('offer_banner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('image_storage_type');
            $table->dropColumn('banner_storage_type');
            $table->dropColumn('bottom_banner_storage_type');
            $table->dropColumn('offer_banner_storage_type');
        });
    }
};

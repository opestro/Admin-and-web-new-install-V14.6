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
        Schema::table('products', function (Blueprint $table) {
            $table->string('thumbnail_storage_type',10)->default('public')->after('thumbnail')->nullable();
            $table->string('digital_file_ready_storage_type',10)->default('public')->after('digital_file_ready')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('thumbnail_storage_type');
            $table->dropColumn('digital_file_ready_storage_type');
        });
    }
};

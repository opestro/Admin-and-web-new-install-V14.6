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
        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->string('country_code', 20)->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });
    }
};

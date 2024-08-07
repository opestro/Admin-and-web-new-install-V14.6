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
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->tinyInteger('approved_count')->after('status')->default(0);
            $table->tinyInteger('denied_count')->after('approved_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropColumn('approved_count');
            $table->dropColumn('denied_count');
        });
    }
};

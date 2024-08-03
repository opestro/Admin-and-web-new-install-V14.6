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
        Schema::table('chattings', function (Blueprint $table) {
            $table->boolean('seen_notification')->after('status')->default(0)->nullable();
            $table->string('notification_receiver', 20)->after('status')->nullable()->comment('admin, seller, customer, deliveryman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chattings', function (Blueprint $table) {
            $table->dropColumn('seen_notification');
            $table->dropColumn('notification_receiver');
        });
    }
};

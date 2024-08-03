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
        Schema::create('digital_product_variations', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('variant_key')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 24, 8)->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_product_variations');
    }
};

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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->string('user_type');
            $table->string('template_design_name');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('image')->nullable();
            $table->string('logo')->nullable();
            $table->string('button_name')->nullable();
            $table->string('button_url')->nullable();
            $table->string('footer_text')->nullable();
            $table->string('copyright_text')->nullable();
            $table->json('pages')->nullable();
            $table->json('social_media')->nullable();
            $table->json('hide_field')->nullable();
            $table->tinyInteger('button_content_status')->default(1);
            $table->tinyInteger('product_information_status')->default(1);
            $table->tinyInteger('order_information_status')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};

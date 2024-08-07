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
        Schema::create('robots_meta_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->nullable();
            $table->string('page_name')->nullable();
            $table->string('page_url')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('canonicals_url')->nullable();
            $table->string('index')->nullable();
            $table->string('no_follow')->nullable();
            $table->string('no_image_index')->nullable();
            $table->string('no_archive')->nullable();
            $table->string('no_snippet')->nullable();
            $table->string('max_snippet')->nullable();
            $table->string('max_snippet_value')->nullable();
            $table->string('max_video_preview')->nullable();
            $table->string('max_video_preview_value')->nullable();
            $table->string('max_image_preview')->nullable();
            $table->string('max_image_preview_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robots_meta_contents');
    }
};

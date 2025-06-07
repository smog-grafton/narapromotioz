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
        Schema::create('hero_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable()->comment('Path to the slider image');
            $table->string('title')->nullable()->comment('Main heading text, can contain HTML');
            $table->text('subtitle')->nullable()->comment('Sub-heading paragraph text');
            $table->string('cta_text')->nullable()->comment('Call-to-action button text');
            $table->string('cta_link')->nullable()->comment('Call-to-action button URL');
            $table->integer('order')->default(0)->comment('Display order of slides');
            $table->boolean('is_active')->default(true)->comment('Toggle slide visibility');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_sliders');
    }
};

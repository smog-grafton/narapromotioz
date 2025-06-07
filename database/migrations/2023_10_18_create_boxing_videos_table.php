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
        Schema::create('boxing_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video_type'); // youtube, vimeo, uploaded, twitch, etc.
            $table->string('video_id')->nullable(); // YouTube/Vimeo ID
            $table->string('video_url')->nullable(); // Full URL
            $table->string('video_path')->nullable(); // Path for uploaded videos
            $table->string('duration')->nullable(); // Video duration
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('published'); // published, draft, archived
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->json('tags')->nullable();
            $table->json('meta_data')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
            
            $table->index('video_type');
            $table->index('is_premium');
            $table->index('is_featured');
            $table->index('status');
            $table->index('category');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boxing_videos');
    }
}; 
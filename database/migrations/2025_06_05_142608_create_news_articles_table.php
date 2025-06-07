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
        if (!Schema::hasTable('news_articles')) {
            Schema::create('news_articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->string('featured_image')->nullable();
                $table->string('author_name')->nullable();
                $table->string('author_email')->nullable();
                $table->string('author_image')->nullable();
                $table->string('status')->default('draft'); // draft, published, archived
                $table->timestamp('published_at')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->boolean('allow_comments')->default(true);
                $table->integer('views_count')->default(0);
                $table->integer('comments_count')->default(0);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->integer('reading_time')->nullable(); // in minutes
                $table->json('seo_data')->nullable();
                $table->timestamps();

                $table->index(['status', 'published_at']);
                $table->index(['is_featured', 'published_at']);
                $table->index('views_count');
                $table->fullText(['title', 'content']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};

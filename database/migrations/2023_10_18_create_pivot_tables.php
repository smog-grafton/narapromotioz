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
        // Boxers and Events (many-to-many)
        Schema::create('boxer_boxing_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boxer_id')->constrained()->onDelete('cascade');
            $table->foreignId('boxing_event_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable(); // main event, co-main, undercard, etc.
            $table->boolean('is_attending')->default(true);
            $table->timestamps();

            $table->unique(['boxer_id', 'boxing_event_id']);
        });

        // Boxers and Videos (many-to-many)
        Schema::create('boxer_boxing_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boxer_id')->constrained()->onDelete('cascade');
            $table->foreignId('boxing_video_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['boxer_id', 'boxing_video_id']);
        });

        // Events and Videos (many-to-many)
        Schema::create('boxing_event_boxing_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boxing_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('boxing_video_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['boxing_event_id', 'boxing_video_id']);
        });

        // Boxers and News (many-to-many)
        Schema::create('boxer_news_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boxer_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_article_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['boxer_id', 'news_article_id']);
        });

        // Events and News (many-to-many)
        Schema::create('boxing_event_news_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boxing_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_article_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['boxing_event_id', 'news_article_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boxer_boxing_event');
        Schema::dropIfExists('boxer_boxing_video');
        Schema::dropIfExists('boxing_event_boxing_video');
        Schema::dropIfExists('boxer_news_article');
        Schema::dropIfExists('boxing_event_news_article');
    }
}; 
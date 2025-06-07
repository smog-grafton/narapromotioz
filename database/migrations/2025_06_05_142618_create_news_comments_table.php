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
        if (!Schema::hasTable('news_comments')) {
            Schema::create('news_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('news_article_id')->constrained()->onDelete('cascade');
                $table->foreignId('parent_id')->nullable()->constrained('news_comments')->onDelete('cascade');
                $table->string('user_name');
                $table->string('user_email');
                $table->string('user_avatar')->nullable();
                $table->text('content');
                $table->string('status')->default('pending'); // pending, approved, spam, rejected
                $table->ipAddress('user_ip')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();

                $table->index(['news_article_id', 'status']);
                $table->index(['parent_id', 'status']);
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_comments');
    }
};

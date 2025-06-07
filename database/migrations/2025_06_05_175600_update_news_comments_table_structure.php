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
        Schema::table('news_comments', function (Blueprint $table) {
            // Rename columns to match our model expectations
            if (Schema::hasColumn('news_comments', 'user_name') && !Schema::hasColumn('news_comments', 'name')) {
                $table->renameColumn('user_name', 'name');
            }
            if (Schema::hasColumn('news_comments', 'user_email') && !Schema::hasColumn('news_comments', 'email')) {
                $table->renameColumn('user_email', 'email');
            }
            if (Schema::hasColumn('news_comments', 'content') && !Schema::hasColumn('news_comments', 'comment')) {
                $table->renameColumn('content', 'comment');
            }
            if (Schema::hasColumn('news_comments', 'news_article_id') && !Schema::hasColumn('news_comments', 'news_id')) {
                $table->renameColumn('news_article_id', 'news_id');
            }
            
            // Add missing website column if it doesn't exist
            if (!Schema::hasColumn('news_comments', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_comments', function (Blueprint $table) {
            // Reverse the column renames
            if (Schema::hasColumn('news_comments', 'name') && !Schema::hasColumn('news_comments', 'user_name')) {
                $table->renameColumn('name', 'user_name');
            }
            if (Schema::hasColumn('news_comments', 'email') && !Schema::hasColumn('news_comments', 'user_email')) {
                $table->renameColumn('email', 'user_email');
            }
            if (Schema::hasColumn('news_comments', 'comment') && !Schema::hasColumn('news_comments', 'content')) {
                $table->renameColumn('comment', 'content');
            }
            if (Schema::hasColumn('news_comments', 'news_id') && !Schema::hasColumn('news_comments', 'news_article_id')) {
                $table->renameColumn('news_id', 'news_article_id');
            }
            
            // Remove website column if it exists
            if (Schema::hasColumn('news_comments', 'website')) {
                $table->dropColumn('website');
            }
        });
    }
};

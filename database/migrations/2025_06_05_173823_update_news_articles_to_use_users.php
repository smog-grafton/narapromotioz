<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news_articles', function (Blueprint $table) {
            if (!Schema::hasColumn('news_articles', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
            }
        });

        // Migrate existing author data to users if needed
        $articles = DB::table('news_articles')->whereNotNull('author_name')->get();
        foreach ($articles as $article) {
            // Find or create user based on author_email
            $user = DB::table('users')->where('email', $article->author_email)->first();
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'name' => $article->author_name,
                    'email' => $article->author_email,
                    'password' => bcrypt('password'), // Default password
                    'avatar' => $article->author_image,
                    'role' => 'author',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $userId = $user->id;
            }
            
            // Update article with user_id
            DB::table('news_articles')->where('id', $article->id)->update(['user_id' => $userId]);
        }

        // Drop author columns after migration
        Schema::table('news_articles', function (Blueprint $table) {
            if (Schema::hasColumn('news_articles', 'author_name')) {
                $table->dropColumn('author_name');
            }
            if (Schema::hasColumn('news_articles', 'author_email')) {
                $table->dropColumn('author_email');
            }
            if (Schema::hasColumn('news_articles', 'author_image')) {
                $table->dropColumn('author_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_articles', function (Blueprint $table) {
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->string('author_image')->nullable();
        });

        Schema::table('news_articles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

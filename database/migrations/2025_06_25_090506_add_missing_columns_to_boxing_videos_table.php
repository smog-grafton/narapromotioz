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
        Schema::table('boxing_videos', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('boxing_videos', 'source_type')) {
                $table->enum('source_type', ['youtube', 'vimeo', 'uploaded', 'external'])->default('youtube');
            }
            
            if (!Schema::hasColumn('boxing_videos', 'publish_date')) {
                $table->timestamp('publish_date')->nullable();
            }
            
            if (!Schema::hasColumn('boxing_videos', 'premium')) {
                $table->boolean('premium')->default(false);
            }
            
            if (!Schema::hasColumn('boxing_videos', 'featured')) {
                $table->boolean('featured')->default(false);
            }
            
            if (!Schema::hasColumn('boxing_videos', 'metadata')) {
                $table->json('metadata')->nullable();
            }
        });

        // Now migrate data from old columns to new ones if needed
        if (Schema::hasColumn('boxing_videos', 'is_premium') && Schema::hasColumn('boxing_videos', 'premium')) {
            DB::statement('UPDATE boxing_videos SET premium = is_premium');
        }
        
        if (Schema::hasColumn('boxing_videos', 'is_featured') && Schema::hasColumn('boxing_videos', 'featured')) {
            DB::statement('UPDATE boxing_videos SET featured = is_featured');
        }
        
        if (Schema::hasColumn('boxing_videos', 'meta_data') && Schema::hasColumn('boxing_videos', 'metadata')) {
            DB::statement('UPDATE boxing_videos SET metadata = meta_data');
        }
        
        if (Schema::hasColumn('boxing_videos', 'published_at') && Schema::hasColumn('boxing_videos', 'publish_date')) {
            DB::statement('UPDATE boxing_videos SET publish_date = published_at');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_videos', function (Blueprint $table) {
            if (Schema::hasColumn('boxing_videos', 'source_type')) {
                $table->dropColumn('source_type');
            }
            if (Schema::hasColumn('boxing_videos', 'publish_date')) {
                $table->dropColumn('publish_date');
            }
            if (Schema::hasColumn('boxing_videos', 'premium')) {
                $table->dropColumn('premium');
            }
            if (Schema::hasColumn('boxing_videos', 'featured')) {
                $table->dropColumn('featured');
            }
            if (Schema::hasColumn('boxing_videos', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};

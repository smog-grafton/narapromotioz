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
        Schema::table('boxing_videos', function (Blueprint $table) {
            // Check and add missing fields only if they don't exist
            if (!Schema::hasColumn('boxing_videos', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('thumbnail');
            }
            
            if (!Schema::hasColumn('boxing_videos', 'boxer_id')) {
                $table->unsignedBigInteger('boxer_id')->nullable()->after('video_path');
                $table->foreign('boxer_id')->references('id')->on('boxers')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('boxing_videos', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable()->after('boxer_id');
                $table->foreign('event_id')->references('id')->on('boxing_events')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_videos', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('boxing_videos', 'boxer_id')) {
                $table->dropForeign(['boxer_id']);
            }
            
            if (Schema::hasColumn('boxing_videos', 'event_id')) {
                $table->dropForeign(['event_id']);
            }
            
            // Then drop columns
            $table->dropColumn(['thumbnail_path', 'boxer_id', 'event_id']);
        });
    }
};

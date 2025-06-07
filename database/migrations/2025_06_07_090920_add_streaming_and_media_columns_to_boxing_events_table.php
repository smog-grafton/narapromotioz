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
        Schema::table('boxing_events', function (Blueprint $table) {
            // Streaming-related columns
            if (!Schema::hasColumn('boxing_events', 'stream_url')) {
                $table->text('stream_url')->nullable()->after('has_stream');
            }
            if (!Schema::hasColumn('boxing_events', 'stream_backup_url')) {
                $table->text('stream_backup_url')->nullable()->after('stream_url');
            }
            if (!Schema::hasColumn('boxing_events', 'youtube_stream_id')) {
                $table->string('youtube_stream_id')->nullable()->after('stream_backup_url');
            }
            if (!Schema::hasColumn('boxing_events', 'stream_password')) {
                $table->string('stream_password')->nullable()->after('youtube_stream_id');
            }
            if (!Schema::hasColumn('boxing_events', 'stream_starts_at')) {
                $table->datetime('stream_starts_at')->nullable()->after('stream_password');
            }
            if (!Schema::hasColumn('boxing_events', 'stream_ends_at')) {
                $table->datetime('stream_ends_at')->nullable()->after('stream_starts_at');
            }

            // Media-related columns
            if (!Schema::hasColumn('boxing_events', 'weigh_in_photos')) {
                $table->longText('weigh_in_photos')->nullable()->after('photos');
            }
            if (!Schema::hasColumn('boxing_events', 'press_conference_photos')) {
                $table->longText('press_conference_photos')->nullable()->after('weigh_in_photos');
            }
            if (!Schema::hasColumn('boxing_events', 'behind_scenes_photos')) {
                $table->longText('behind_scenes_photos')->nullable()->after('press_conference_photos');
            }
            if (!Schema::hasColumn('boxing_events', 'highlight_videos')) {
                $table->longText('highlight_videos')->nullable()->after('behind_scenes_photos');
            }
            if (!Schema::hasColumn('boxing_events', 'gallery_videos')) {
                $table->longText('gallery_videos')->nullable()->after('highlight_videos');
            }

            // Event additional info
            if (!Schema::hasColumn('boxing_events', 'full_description')) {
                $table->longText('full_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('boxing_events', 'stream_price')) {
                $table->decimal('stream_price', 10, 2)->nullable()->after('ppv_price');
            }
            if (!Schema::hasColumn('boxing_events', 'early_access_stream')) {
                $table->boolean('early_access_stream')->default(false)->after('stream_price');
            }
            if (!Schema::hasColumn('boxing_events', 'require_ticket_for_stream')) {
                $table->boolean('require_ticket_for_stream')->default(true)->after('early_access_stream');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_events', function (Blueprint $table) {
            $columnsToCheck = [
                'stream_url', 'stream_backup_url', 'youtube_stream_id', 'stream_password',
                'stream_starts_at', 'stream_ends_at', 'weigh_in_photos', 'press_conference_photos',
                'behind_scenes_photos', 'highlight_videos', 'gallery_videos', 'stream_price',
                'early_access_stream', 'require_ticket_for_stream'
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('boxing_events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

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
            // Check if thumbnail_path column exists before dropping it
            if (Schema::hasColumn('boxing_videos', 'thumbnail_path')) {
                $table->dropColumn('thumbnail_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_videos', function (Blueprint $table) {
            // Re-add thumbnail_path column if rolling back
            if (!Schema::hasColumn('boxing_videos', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('thumbnail');
            }
        });
    }
};

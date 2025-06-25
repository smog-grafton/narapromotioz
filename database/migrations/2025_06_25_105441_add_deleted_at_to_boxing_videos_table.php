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
            // Check if deleted_at column doesn't exist before adding it
            if (!Schema::hasColumn('boxing_videos', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_videos', function (Blueprint $table) {
            // Check if deleted_at column exists before dropping it
            if (Schema::hasColumn('boxing_videos', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

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
            // Check if view_count column doesn't exist before adding it
            if (!Schema::hasColumn('boxing_events', 'view_count')) {
                $table->unsignedInteger('view_count')->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_events', function (Blueprint $table) {
            // Check if view_count column exists before dropping it
            if (Schema::hasColumn('boxing_events', 'view_count')) {
                $table->dropColumn('view_count');
            }
        });
    }
};

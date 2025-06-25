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
            // Check if broadcast_network column doesn't exist before adding it
            if (!Schema::hasColumn('boxing_events', 'broadcast_network')) {
                $table->string('broadcast_network')->nullable()->after('network');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_events', function (Blueprint $table) {
            // Check if broadcast_network column exists before dropping it
            if (Schema::hasColumn('boxing_events', 'broadcast_network')) {
                $table->dropColumn('broadcast_network');
            }
        });
    }
};

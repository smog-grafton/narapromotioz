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
        Schema::table('fight_records', function (Blueprint $table) {
            // Check if order column doesn't exist before adding it
            if (!Schema::hasColumn('fight_records', 'order')) {
                $table->integer('order')->default(0)->after('is_main_event')->comment('Order of fights in the event');
                $table->index(['boxing_event_id', 'order']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fight_records', function (Blueprint $table) {
            if (Schema::hasColumn('fight_records', 'order')) {
                $table->dropIndex(['boxing_event_id', 'order']);
                $table->dropColumn('order');
            }
        });
    }
};

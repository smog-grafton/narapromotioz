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
        Schema::table('event_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('event_tickets', 'max_per_purchase')) {
                $table->integer('max_per_purchase')->default(10)->after('quantity_sold');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('event_tickets', 'max_per_purchase')) {
                $table->dropColumn('max_per_purchase');
            }
        });
    }
};

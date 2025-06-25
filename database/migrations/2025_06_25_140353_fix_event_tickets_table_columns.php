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
        Schema::table('event_tickets', function (Blueprint $table) {
            // Add status column if it doesn't exist (model expects 'status' but DB has 'is_active')
            if (!Schema::hasColumn('event_tickets', 'status')) {
                $table->string('status')->default('active')->after('max_per_purchase');
            }
            
            // Add ticket_template_id column if it doesn't exist
            if (!Schema::hasColumn('event_tickets', 'ticket_template_id')) {
                $table->unsignedBigInteger('ticket_template_id')->nullable()->after('boxing_event_id');
            }
            
            // Add is_featured column if it doesn't exist
            if (!Schema::hasColumn('event_tickets', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('ticket_features');
            }
            
            // Rename sale_starts_at to sale_start_date if needed
            if (Schema::hasColumn('event_tickets', 'sale_starts_at') && !Schema::hasColumn('event_tickets', 'sale_start_date')) {
                $table->renameColumn('sale_starts_at', 'sale_start_date');
            }
            
            // Rename sale_ends_at to sale_end_date if needed
            if (Schema::hasColumn('event_tickets', 'sale_ends_at') && !Schema::hasColumn('event_tickets', 'sale_end_date')) {
                $table->renameColumn('sale_ends_at', 'sale_end_date');
            }
        });
        
        // Copy data from is_active to status column
        if (Schema::hasColumn('event_tickets', 'is_active') && Schema::hasColumn('event_tickets', 'status')) {
            DB::statement("UPDATE event_tickets SET status = CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END WHERE status = 'active'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tickets', function (Blueprint $table) {
            // Remove added columns if they exist
            if (Schema::hasColumn('event_tickets', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('event_tickets', 'ticket_template_id')) {
                $table->dropColumn('ticket_template_id');
            }
            
            if (Schema::hasColumn('event_tickets', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            
            // Rename columns back
            if (Schema::hasColumn('event_tickets', 'sale_start_date') && !Schema::hasColumn('event_tickets', 'sale_starts_at')) {
                $table->renameColumn('sale_start_date', 'sale_starts_at');
            }
            
            if (Schema::hasColumn('event_tickets', 'sale_end_date') && !Schema::hasColumn('event_tickets', 'sale_ends_at')) {
                $table->renameColumn('sale_end_date', 'sale_ends_at');
            }
        });
    }
};

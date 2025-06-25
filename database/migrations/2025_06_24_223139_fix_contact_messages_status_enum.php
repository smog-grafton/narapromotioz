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
        // Add 'new' to the enum values for status
        DB::statement("ALTER TABLE contact_messages MODIFY COLUMN status ENUM('new', 'unread', 'read', 'replied') DEFAULT 'new'");
        
        // Update existing 'unread' records to 'new' for consistency
        DB::table('contact_messages')
            ->where('status', 'unread')
            ->update(['status' => 'new']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::table('contact_messages')
            ->where('status', 'new')
            ->update(['status' => 'unread']);
            
        DB::statement("ALTER TABLE contact_messages MODIFY COLUMN status ENUM('unread', 'read', 'replied') DEFAULT 'unread'");
    }
};

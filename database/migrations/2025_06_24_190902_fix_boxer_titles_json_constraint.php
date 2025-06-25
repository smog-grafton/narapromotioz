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
        // Fix any existing records with invalid JSON in titles field
        DB::statement("UPDATE boxers SET titles = '[]' WHERE titles = '' OR titles IS NULL OR titles = 'null'");
        
        // Also fix any records that might have invalid JSON
        $boxers = DB::table('boxers')->get();
        foreach ($boxers as $boxer) {
            if ($boxer->titles) {
                $decoded = json_decode($boxer->titles, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // If JSON is invalid, set to empty array
                    DB::table('boxers')
                        ->where('id', $boxer->id)
                        ->update(['titles' => '[]']);
                }
            } else {
                // If titles is null or empty, set to empty array
                DB::table('boxers')
                    ->where('id', $boxer->id)
                    ->update(['titles' => '[]']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration doesn't need to be reversed
        // as it only fixes data consistency
    }
};

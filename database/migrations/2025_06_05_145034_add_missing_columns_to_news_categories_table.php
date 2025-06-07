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
        Schema::table('news_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('news_categories', 'color')) {
                $table->string('color')->default('#007bff')->after('description');
            }
            if (!Schema::hasColumn('news_categories', 'icon')) {
                $table->string('icon')->nullable()->after('color');
            }
            if (!Schema::hasColumn('news_categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('icon');
            }
            if (!Schema::hasColumn('news_categories', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });

        // Add index if it doesn't exist
        if (!Schema::hasColumn('news_categories', 'is_active') || !Schema::hasColumn('news_categories', 'sort_order')) {
            Schema::table('news_categories', function (Blueprint $table) {
                $table->index(['is_active', 'sort_order']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_categories', function (Blueprint $table) {
            $table->dropColumn(['color', 'icon', 'is_active', 'sort_order']);
            $table->dropIndex(['is_active', 'sort_order']);
        });
    }
};

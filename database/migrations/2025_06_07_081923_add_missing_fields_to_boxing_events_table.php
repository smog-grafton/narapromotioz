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
            // Check and add missing fields only if they don't exist
            if (!Schema::hasColumn('boxing_events', 'tagline')) {
                $table->string('tagline')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('boxing_events', 'event_type')) {
                $table->string('event_type')->default('regular')->after('status');
            }
            
            if (!Schema::hasColumn('boxing_events', 'weight_class')) {
                $table->string('weight_class')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('boxing_events', 'main_event_boxer_1_id')) {
                $table->unsignedBigInteger('main_event_boxer_1_id')->nullable()->after('is_featured');
            }
            
            if (!Schema::hasColumn('boxing_events', 'main_event_boxer_2_id')) {
                $table->unsignedBigInteger('main_event_boxer_2_id')->nullable()->after('main_event_boxer_1_id');
            }
            
            if (!Schema::hasColumn('boxing_events', 'title')) {
                $table->string('title')->nullable()->after('weight_class');
            }
            
            if (!Schema::hasColumn('boxing_events', 'rounds')) {
                $table->integer('rounds')->default(12)->after('title');
            }
            
            if (!Schema::hasColumn('boxing_events', 'ppv_price')) {
                $table->decimal('ppv_price', 10, 2)->nullable()->after('broadcast_type');
            }
            
            if (!Schema::hasColumn('boxing_events', 'is_ppv')) {
                $table->boolean('is_ppv')->default(false)->after('is_featured');
            }
            
            if (!Schema::hasColumn('boxing_events', 'promo_video_url')) {
                $table->string('promo_video_url')->nullable()->after('banner_path');
            }
            
            if (!Schema::hasColumn('boxing_events', 'poster_image')) {
                $table->string('poster_image')->nullable()->after('promo_video_url');
            }
            
            if (!Schema::hasColumn('boxing_events', 'promo_images')) {
                $table->json('promo_images')->nullable()->after('poster_image');
            }
            
            if (!Schema::hasColumn('boxing_events', 'photos')) {
                $table->json('photos')->nullable()->after('promo_images');
            }
            
            if (!Schema::hasColumn('boxing_events', 'sponsors')) {
                $table->json('sponsors')->nullable()->after('photos');
            }
            
            if (!Schema::hasColumn('boxing_events', 'has_stream')) {
                $table->boolean('has_stream')->default(false)->after('is_ppv');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boxing_events', function (Blueprint $table) {
            // Drop the columns in reverse order
            $table->dropColumn([
                'tagline',
                'event_type',
                'weight_class',
                'title',
                'rounds',
                'main_event_boxer_1_id',
                'main_event_boxer_2_id',
                'ppv_price',
                'is_ppv',
                'promo_video_url',
                'poster_image',
                'promo_images',
                'photos',
                'sponsors',
                'has_stream',
            ]);
        });
    }
};

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
        // Update existing videos to extract video IDs from URLs
        $videos = DB::table('boxing_videos')->whereNotNull('video_url')->get();
        
        foreach ($videos as $video) {
            $videoId = $this->extractVideoId($video->video_url, $video->source_type);
            
            if ($videoId) {
                DB::table('boxing_videos')
                    ->where('id', $video->id)
                    ->update(['video_id' => $videoId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear video_id field
        DB::table('boxing_videos')->update(['video_id' => null]);
    }
    
    /**
     * Extract video ID from URL based on source type
     */
    private function extractVideoId($url, $sourceType)
    {
        if (!$url) return null;
        
        switch ($sourceType) {
            case 'youtube':
                // Extract YouTube video ID from various URL formats
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                    return $matches[1];
                }
                break;
                
            case 'vimeo':
                // Extract Vimeo video ID
                if (preg_match('/vimeo\.com\/(?:channels\/[^\/]+\/|groups\/[^\/]+\/videos\/|album\/\d+\/video\/|video\/|)(\d+)(?:$|\/|\?)/', $url, $matches)) {
                    return $matches[1];
                }
                break;
        }
        
        return null;
    }
};

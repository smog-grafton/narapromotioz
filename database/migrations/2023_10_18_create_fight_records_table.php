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
        Schema::create('fight_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boxer_id')->constrained()->onDelete('cascade');
            $table->foreignId('opponent_id')->nullable()->constrained('boxers')->nullOnDelete();
            $table->foreignId('boxing_event_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fight_date');
            $table->string('result'); // win, loss, draw, no contest
            $table->string('method')->nullable(); // KO, TKO, UD, SD, etc.
            $table->integer('rounds')->nullable();
            $table->string('round_time')->nullable();
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            $table->text('notes')->nullable();
            $table->string('title_fight')->nullable(); // Name of the title if it was a title fight
            $table->string('weight_class')->nullable();
            $table->boolean('is_main_event')->default(false);
            $table->string('referee')->nullable();
            $table->json('judges')->nullable();
            $table->json('scorecards')->nullable();
            $table->string('video_id')->nullable(); // Reference to a boxing_video if available
            $table->string('image_path')->nullable();
            $table->timestamps();
            
            $table->index(['boxer_id', 'result']);
            $table->index(['boxer_id', 'fight_date']);
            $table->index('opponent_id');
            $table->index('boxing_event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fight_records');
    }
}; 
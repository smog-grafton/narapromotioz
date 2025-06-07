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
        Schema::create('boxing_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('full_description')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('venue');
            $table->string('city');
            $table->string('country');
            $table->string('address')->nullable();
            $table->string('network')->nullable();
            $table->string('broadcast_type')->nullable(); // e.g., 'PPV', 'ESPN+', 'DAZN', etc.
            $table->string('image_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('status')->default('upcoming'); // upcoming, past, cancelled
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free')->default(false); // If tickets are free
            $table->boolean('tickets_available')->default(true);
            $table->boolean('live_gate_open')->default(true); // If tickets can be purchased at the venue
            $table->decimal('min_ticket_price', 10, 2)->nullable();
            $table->decimal('max_ticket_price', 10, 2)->nullable();
            $table->string('ticket_purchase_url')->nullable();
            $table->json('meta_data')->nullable(); // Additional metadata
            $table->string('organizer')->nullable();
            $table->string('promoter')->nullable();
            $table->string('sanctioning_body')->nullable(); // WBC, WBA, IBF, etc.
            $table->integer('views_count')->default(0);
            $table->timestamps();
            
            $table->index('event_date');
            $table->index('status');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boxing_events');
    }
}; 
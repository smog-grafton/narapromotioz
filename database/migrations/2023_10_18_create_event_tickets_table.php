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
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boxing_event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., 'VIP', 'General Admission', 'Ringside', etc.
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity_available')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('image_path')->nullable(); // Ticket template image
            $table->string('ticket_type')->default('regular'); // regular, vip, table, etc.
            $table->json('seating_info')->nullable(); // JSON with seating information
            $table->dateTime('sale_starts_at')->nullable();
            $table->dateTime('sale_ends_at')->nullable();
            $table->integer('max_per_purchase')->default(10);
            $table->boolean('transferable')->default(true); // Can be transferred to another person
            $table->json('benefits')->nullable(); // Special benefits like free drink, merchandise, etc.
            $table->timestamps();
            
            $table->index(['boxing_event_id', 'ticket_type']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
}; 
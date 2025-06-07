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
        Schema::create('ticket_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('image_path'); // Background image for the ticket
            $table->integer('width')->default(800); // Width in pixels
            $table->integer('height')->default(350); // Height in pixels
            $table->json('qr_code_position'); // {x, y, width, height} for QR code placement
            $table->json('text_fields')->nullable(); // Array of text field positions {field_name, x, y, font_size, color}
            $table->string('ticket_type')->default('regular'); // regular, vip, table, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Creator
            $table->timestamps();
            
            $table->index('ticket_type');
            $table->index('is_active');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_templates');
    }
}; 
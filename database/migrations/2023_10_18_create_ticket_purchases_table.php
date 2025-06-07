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
        Schema::create('ticket_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Null if guest purchase
            $table->foreignId('event_ticket_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('qr_code')->unique()->nullable();
            $table->string('ticket_holder_name');
            $table->string('ticket_holder_email');
            $table->string('ticket_holder_phone')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->string('status'); // pending, completed, cancelled, refunded
            $table->string('payment_method')->nullable(); // pesapal, manual, cash, etc.
            $table->string('payment_id')->nullable(); // External payment reference
            $table->json('payment_details')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('ticket_generated_at')->nullable();
            $table->dateTime('ticket_sent_at')->nullable();
            $table->string('ticket_pdf_path')->nullable();
            $table->boolean('is_checked_in')->default(false);
            $table->dateTime('checked_in_at')->nullable();
            $table->string('checked_in_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('qr_code');
            $table->index('status');
            $table->index('ticket_holder_email');
            $table->index(['event_ticket_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_purchases');
    }
}; 
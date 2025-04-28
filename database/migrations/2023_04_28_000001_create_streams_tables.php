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
        // Main streams table
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('scheduled_start')->nullable();
            $table->timestamp('scheduled_end')->nullable();
            $table->timestamp('actual_start')->nullable();
            $table->timestamp('actual_end')->nullable();
            $table->string('status')->default('scheduled');
            $table->string('access_level')->default('free');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('stream_key')->unique();
            $table->string('playback_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('ingest_server')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('viewer_count')->default(0);
            $table->integer('max_viewer_count')->default(0);
            $table->json('stream_meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Stream purchases
        Schema::create('stream_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('promo_code')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('status')->default('active');
            $table->string('purchase_code')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Stream chat messages
        Schema::create('stream_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });

        // Stream viewers (analytics)
        Schema::create('stream_viewers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('first_joined_at');
            $table->timestamp('last_active_at');
            $table->integer('view_count')->default(1);
            $table->decimal('total_watch_time', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['stream_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stream_viewers');
        Schema::dropIfExists('stream_chat_messages');
        Schema::dropIfExists('stream_purchases');
        Schema::dropIfExists('streams');
    }
};
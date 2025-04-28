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
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fighter_id')->constrained()->onDelete('cascade');
            $table->string('weight_class');
            $table->integer('position');
            $table->decimal('points', 8, 2);
            $table->dateTime('last_updated');
            $table->timestamps();

            // Unique constraint to ensure a fighter has only one ranking per weight class
            $table->unique(['fighter_id', 'weight_class']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
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
        Schema::create('boxers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('nickname')->nullable();
            $table->string('weight_class');
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('knockouts')->default(0);
            $table->integer('kos_lost')->default(0);
            $table->integer('age')->nullable();
            $table->string('height')->nullable();
            $table->string('reach')->nullable();
            $table->string('stance')->nullable();
            $table->string('hometown')->nullable();
            $table->string('country')->nullable();
            $table->text('bio')->nullable();
            $table->longText('full_bio')->nullable();
            $table->string('image_path')->nullable();
            $table->json('titles')->nullable();
            $table->integer('years_pro')->default(0);
            $table->string('status')->default('Professional');
            $table->integer('global_ranking')->nullable();
            $table->integer('total_fighters_in_division')->nullable();
            $table->year('career_start')->nullable();
            $table->year('career_end')->nullable();
            $table->date('debut_date')->nullable();
            $table->integer('knockout_rate')->nullable();
            $table->integer('win_rate')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('weight_class');
            $table->index('global_ranking');
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boxers');
    }
}; 
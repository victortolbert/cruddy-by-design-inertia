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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('show_notes')->nullable();
            $table->string('audio_url');
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['podcast_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
